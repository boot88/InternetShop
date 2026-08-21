<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = CartController::currentCart();
        $items = $cart->items()
            ->with(['product.images', 'variant.attributeValues'])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Корзина пуста.']);
        }

        $total = $items->sum(fn ($item) => $item->quantity * $item->price);

        return view('checkout.create', compact('items', 'total'));
    }

    public function store(CheckoutRequest $request)
    {
        $data = $request->validated();
        $cart = CartController::currentCart();

        $order = DB::transaction(function () use ($cart, $data) {
            $items = $cart->items()
                ->with(['product', 'variant.attributeValues'])
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Корзина пуста.']);
            }

            $preparedItems = [];
            $subtotal = 0;

            foreach ($items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                if (!$product || !$product->is_active || ($variant && !$variant->is_active)) {
                    throw ValidationException::withMessages(['cart' => 'Один из товаров больше недоступен. Обновите корзину.']);
                }

                $stock = Stock::query()
                    ->when($variant, fn ($query) => $query->where('variant_id', $variant->id), fn ($query) => $query->where('product_id', $product->id)->whereNull('variant_id'))
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->quantity < $item->quantity) {
                    throw ValidationException::withMessages(['cart' => 'Недостаточно товара «'.$product->name.'» на складе.']);
                }

                $price = (float) ($variant?->final_price ?? $product->final_price);
                $total = $price * $item->quantity;
                $subtotal += $total;
                $preparedItems[] = compact('item', 'product', 'variant', 'stock', 'price', 'total');
            }

            $contact = trim($data['name']).', тел. '.trim($data['phone']);
            if (!empty($data['email'])) {
                $contact .= ', '.$data['email'];
            }

            $order = Order::create([
                'order_number' => 'TZ-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'total' => $subtotal,
                'coupon_id' => $cart->coupon_id,
                'customer_note' => $data['customer_note'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $contact,
                'shipping_method' => 'manager_confirmation',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
            ]);

            foreach ($preparedItems as $prepared) {
                $attributes = $prepared['variant']
                    ? $prepared['variant']->attributeValues->pluck('value')->implode(', ')
                    : null;

                $order->items()->create([
                    'product_id' => $prepared['product']->id,
                    'variant_id' => $prepared['variant']?->id,
                    'product_name' => $prepared['product']->name,
                    'variant_attributes' => $attributes,
                    'quantity' => $prepared['item']->quantity,
                    'price' => $prepared['price'],
                    'total' => $prepared['total'],
                ]);
                $prepared['stock']->decrement('quantity', $prepared['item']->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        session()->put('checkout_last_order_id', $order->id);

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Заказ принят. Мы свяжемся с вами для подтверждения.');
    }

    public function success(Order $order)
    {
        $isOwner = $order->user_id !== null && $order->user_id === Auth::id();
        $isGuestCheckout = $order->user_id === null
            && (int) session('checkout_last_order_id') === $order->id;
        abort_unless($isOwner || $isGuestCheckout, 403);

        return view('checkout.success', compact('order'));
    }
}
