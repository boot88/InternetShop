<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private const MAX_ITEM_QUANTITY = 10;

    public function index()
    {
        $cart = self::currentCart();
        $cartItems = $cart->items()
            ->with(['product.images', 'product.brand', 'product.stock', 'variant.stock', 'variant.attributeValues'])
            ->get();

        $priceChanges = $this->refreshPrices($cartItems);
        $cart->load('coupon');
        [$cartCount, $total, $subtotal, $discount] = $this->calcCartSummary($cart);

        $coupon = $cart->coupon;

        return view('cart.index', compact('cartItems', 'subtotal', 'discount', 'total', 'priceChanges', 'coupon'));
    }

    public static function getCartCountStatic(): int
    {
        $cart = Auth::check()
            ? Cart::where('user_id', Auth::id())->first()
            : Cart::where('session_id', session()->getId())->first();

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }

    public function add(AddToCartRequest $request, Product $product)
    {
        if (! $product->is_active) {
            return $this->failure($request, 'Этот товар больше не продаётся.', 404);
        }

        $variant = $this->resolveVariant($product, $request->integer('variant_id'));
        if ($product->uses_variants && ! $variant) {
            return $this->failure($request, 'Выберите вариант товара.', 422);
        }
        if (! $product->uses_variants && $request->filled('variant_id')) {
            return $this->failure($request, 'У этого товара нет вариантов.', 422);
        }
        if (! $this->isPurchasable($product, $variant)) {
            return $this->failure($request, 'Товара нет в наличии.', 422);
        }

        $cart = self::currentCart();
        $quantity = $request->integer('quantity', 1);
        $existingItemQuery = $cart->items()->where('product_id', $product->id);
        $variant ? $existingItemQuery->where('variant_id', $variant->id) : $existingItemQuery->whereNull('variant_id');
        $existingItem = $existingItemQuery->first();
        $newQuantity = $quantity + ($existingItem?->quantity ?? 0);

        if ($newQuantity > self::MAX_ITEM_QUANTITY) {
            return $this->failure($request, 'В корзину можно добавить не более 10 единиц одного товара.', 422);
        }
        if ($newQuantity > $this->availableQuantity($product, $variant)) {
            return $this->failure($request, 'В наличии осталось меньше товаров, чем вы выбрали.', 422);
        }

        $price = (float) ($variant?->final_price ?? $product->final_price);
        $cartItem = $existingItem ?: new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
        ]);
        $cartItem->quantity = $newQuantity;
        $cartItem->price = $price;
        $cartItem->save();

        [$cartCount, $total, $subtotal, $discount] = $this->calcCartSummary($cart);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'total' => $total,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'item_total' => $price * $newQuantity,
            'message' => 'Товар «'.$product->name.'» добавлен в корзину.',
        ]);
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:'.self::MAX_ITEM_QUANTITY]);
        $cart = self::currentCart();
        $cartItem = $cart->items()->with(['product.stock', 'product.variants', 'variant.stock'])->whereKey($itemId)->firstOrFail();

        if (! $cartItem->product || ! $cartItem->product->is_active || ! $this->isPurchasable($cartItem->product, $cartItem->variant)) {
            return $this->failure($request, 'Этот товар больше недоступен.', 422);
        }
        if ($request->integer('quantity') > $this->availableQuantity($cartItem->product, $cartItem->variant)) {
            return $this->failure($request, 'В наличии осталось меньше товаров, чем вы выбрали.', 422);
        }

        $cartItem->update([
            'quantity' => $request->integer('quantity'),
            'price' => (float) ($cartItem->variant?->final_price ?? $cartItem->product->final_price),
        ]);
        [$cartCount, $total, $subtotal, $discount] = $this->calcCartSummary($cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'item_total' => (float) $cartItem->price * $cartItem->quantity,
                'message' => 'Количество обновлено.',
            ]);
        }

        return back()->with('success', 'Количество обновлено.');
    }

    public function remove(Request $request, int $itemId)
    {
        $cart = self::currentCart();
        $cart->items()->whereKey($itemId)->firstOrFail()->delete();
        [$cartCount, $total, $subtotal, $discount] = $this->calcCartSummary($cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'empty' => $cartCount === 0,
                'message' => 'Товар удалён из корзины.',
            ]);
        }

        return back()->with('success', 'Товар удалён из корзины.');
    }

    public function clear(Request $request)
    {
        $cart = self::currentCart();
        $cart->items()->delete();
        $cart->update(['coupon_id' => null]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'cart_count' => 0, 'total' => 0, 'empty' => true, 'message' => 'Корзина очищена.']);
        }

        return back()->with('success', 'Корзина очищена.');
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50']]);
        $cart = self::currentCart();
        $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($data['code']))])->first();
        $subtotal = (float) $cart->items()
            ->get(['quantity', 'price'])
            ->sum(fn (CartItem $item): float => (float) $item->price * $item->quantity);

        if (! $coupon || ! $coupon->isValid() || $coupon->calculateDiscount($subtotal) <= 0) {
            return back()->withErrors(['coupon' => 'Промокод недействителен или не подходит для этой суммы заказа.']);
        }

        $cart->update(['coupon_id' => $coupon->id]);

        return back()->with('success', 'Промокод применён.');
    }

    public function removeCoupon()
    {
        self::currentCart()->update(['coupon_id' => null]);

        return back()->with('success', 'Промокод удалён.');
    }

    public function getCartCount(): int
    {
        return self::getCartCountStatic();
    }

    public static function currentCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()], ['session_id' => session()->getId()]);
        }

        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    public static function mergeGuestCartForUser(int $userId, ?string $guestSessionId, string $currentSessionId): void
    {
        if (! $guestSessionId) {
            return;
        }

        DB::transaction(function () use ($userId, $guestSessionId, $currentSessionId): void {
            $guestCart = Cart::query()->whereNull('user_id')->where('session_id', $guestSessionId)->with('items')->first();
            $userCart = Cart::firstOrCreate(['user_id' => $userId], ['session_id' => $currentSessionId]);
            $userCart->update([
                'session_id' => $currentSessionId,
                'coupon_id' => $userCart->coupon_id ?: $guestCart?->coupon_id,
            ]);

            if (! $guestCart || $guestCart->is($userCart)) {
                return;
            }

            foreach ($guestCart->items as $guestItem) {
                $existingQuery = $userCart->items()->where('product_id', $guestItem->product_id);
                $guestItem->variant_id ? $existingQuery->where('variant_id', $guestItem->variant_id) : $existingQuery->whereNull('variant_id');
                $existingItem = $existingQuery->first();

                if ($existingItem) {
                    $existingItem->update(['quantity' => min(self::MAX_ITEM_QUANTITY, $existingItem->quantity + $guestItem->quantity)]);
                } else {
                    $userCart->items()->create([
                        'product_id' => $guestItem->product_id,
                        'variant_id' => $guestItem->variant_id,
                        'quantity' => min(self::MAX_ITEM_QUANTITY, $guestItem->quantity),
                        'price' => $guestItem->price,
                    ]);
                }
            }

            $guestCart->items()->delete();
            $guestCart->delete();
        });
    }

    private function refreshPrices($items): array
    {
        $changes = [];
        foreach ($items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                continue;
            }
            $current = (float) ($item->variant?->final_price ?? $item->product->final_price);
            $previous = (float) $item->price;
            if (abs($current - $previous) >= 0.01) {
                $changes[] = ['name' => $item->product->name, 'old' => $previous, 'new' => $current];
                $item->update(['price' => $current]);
            }
        }

        return $changes;
    }

    private function resolveVariant(Product $product, ?int $variantId): ?ProductVariant
    {
        if (! $variantId) {
            return null;
        }

        return $product->variants()->whereKey($variantId)->where('is_active', true)->with('stock')->first();
    }

    private function availableQuantity(Product $product, ?ProductVariant $variant): int
    {
        return max(0, (int) (($variant ? $variant->stock : $product->stock)?->quantity ?? 0));
    }

    private function isPurchasable(Product $product, ?ProductVariant $variant): bool
    {
        return $this->availableQuantity($product, $variant) > 0;
    }

    private function failure(Request $request, string $message, int $status)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return back()->withInput()->withErrors(['cart' => $message]);
    }

    private function calcCartSummary(Cart $cart): array
    {
        $items = $cart->items()->get(['quantity', 'price']);
        $subtotal = (float) $items->sum(fn (CartItem $item) => $item->quantity * $item->price);
        $coupon = $cart->coupon()->first();
        $discount = (float) ($coupon?->calculateDiscount($subtotal) ?? 0);

        return [(int) $items->sum('quantity'), max(0, $subtotal - $discount), $subtotal, $discount];
    }
}
