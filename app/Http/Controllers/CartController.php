<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private const MAX_ITEM_QUANTITY = 10;

    public function index()
    {
        $cart = self::currentCart();
        $cartItems = $cart->items()
            ->with([
                'product.images', 'product.brand', 'product.stock',
                'variant.stock', 'variant.attributeValues',
            ])
            ->get();

        $total = $cartItems->sum(fn (CartItem $item) => $item->quantity * $item->price);

        return view('cart.index', compact('cartItems', 'total'));
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
        if (!$product->is_active) {
            return $this->failure($request, 'Этот товар больше не продаётся.', 404);
        }

        $variant = $this->resolveVariant($product, $request->integer('variant_id'));
        if ($product->has_variants && !$variant) {
            return $this->failure($request, 'Выберите вариант товара.', 422);
        }

        if (!$product->has_variants && $request->filled('variant_id')) {
            return $this->failure($request, 'У этого товара нет вариантов.', 422);
        }

        if (!$this->isPurchasable($product, $variant)) {
            return $this->failure($request, 'Товара нет в наличии.', 422);
        }

        $cart = self::currentCart();
        $quantity = $request->integer('quantity', 1);
        $existingItemQuery = $cart->items()->where('product_id', $product->id);
        $variant
            ? $existingItemQuery->where('variant_id', $variant->id)
            : $existingItemQuery->whereNull('variant_id');
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
            'price' => $price,
        ]);
        $cartItem->quantity = $newQuantity;
        $cartItem->price = $price;
        $cartItem->save();

        [$cartCount, $total] = $this->calcCartSummary($cart);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'total' => $total,
            'item_total' => (float) $cartItem->price * $cartItem->quantity,
            'message' => 'Товар «'.$product->name.'» добавлен в корзину.',
        ]);
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:'.self::MAX_ITEM_QUANTITY]);

        $cart = self::currentCart();
        $cartItem = $cart->items()
            ->with(['product.stock', 'variant.stock'])
            ->whereKey($itemId)
            ->firstOrFail();

        if (!$cartItem->product || !$cartItem->product->is_active || !$this->isPurchasable($cartItem->product, $cartItem->variant)) {
            return $this->failure($request, 'Этот товар больше недоступен.', 422);
        }

        if ($request->integer('quantity') > $this->availableQuantity($cartItem->product, $cartItem->variant)) {
            return $this->failure($request, 'В наличии осталось меньше товаров, чем вы выбрали.', 422);
        }

        $cartItem->update(['quantity' => $request->integer('quantity')]);

        if ($request->expectsJson() || $request->ajax()) {
            [$cartCount, $total] = $this->calcCartSummary($cart);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
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

        if ($request->expectsJson() || $request->ajax()) {
            [$cartCount, $total] = $this->calcCartSummary($cart);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'message' => 'Товар удалён из корзины.',
            ]);
        }

        return back()->with('success', 'Товар удалён из корзины.');
    }

    public function clear(Request $request)
    {
        $cart = self::currentCart();
        $cart->items()->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => 0,
                'total' => 0,
                'message' => 'Корзина очищена.',
            ]);
        }

        return back()->with('success', 'Корзина очищена.');
    }

    public function getCartCount(): int
    {
        return self::getCartCountStatic();
    }

    public static function currentCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => session()->getId()]
            );
        }

        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }

    private function resolveVariant(Product $product, ?int $variantId): ?ProductVariant
    {
        if (!$variantId) {
            return null;
        }

        return $product->variants()
            ->whereKey($variantId)
            ->where('is_active', true)
            ->with('stock')
            ->first();
    }

    private function availableQuantity(Product $product, ?ProductVariant $variant): int
    {
        $stock = $variant ? $variant->stock : $product->stock;

        return max(0, (int) ($stock?->quantity ?? 0));
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

        return [
            (int) $items->sum('quantity'),
            (float) $items->sum(fn (CartItem $item) => $item->quantity * $item->price),
        ];
    }
}
