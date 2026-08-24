<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = CartController::currentCart();
        $items = $cart->items()->with(['product.images', 'product.variants', 'variant.attributeValues'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Корзина пуста.']);
        }

        foreach ($items as $item) {
            if ($item->product?->is_active) {
                $item->update(['price' => (float) ($item->variant?->final_price ?? $item->product->final_price)]);
            }
        }
        $subtotal = (float) $items->sum(fn ($item) => $item->quantity * $item->price);
        $coupon = $cart->coupon;
        $discount = (float) ($coupon?->calculateDiscount($subtotal) ?? 0);
        $total = max(0, $subtotal - $discount);

        return view('checkout.create', compact('items', 'subtotal', 'discount', 'total', 'coupon'));
    }

    public function store(CheckoutRequest $request)
    {
        $data = $request->validated();
        $data['phone'] = preg_replace('/[^+0-9]/', '', $data['phone']);
        if (strlen(preg_replace('/\D/', '', $data['phone'])) < 7) {
            throw ValidationException::withMessages(['phone' => 'Укажите корректный номер телефона.']);
        }
        $cart = CartController::currentCart();

        $order = DB::transaction(function () use ($cart, $data) {
            $items = $cart->items()->with(['product', 'variant.attributeValues'])->lockForUpdate()->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Корзина пуста.']);
            }

            $preparedItems = [];
            $subtotal = 0;
            foreach ($items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                if (! $product || ! $product->is_active || ($variant && ! $variant->is_active)) {
                    throw ValidationException::withMessages(['cart' => 'Один из товаров больше недоступен. Обновите корзину.']);
                }

                $stock = Stock::query()
                    ->when($variant, fn ($query) => $query->where('variant_id', $variant->id), fn ($query) => $query->where('product_id', $product->id)->whereNull('variant_id'))
                    ->lockForUpdate()
                    ->first();
                if (! $stock || $stock->quantity < $item->quantity) {
                    throw ValidationException::withMessages(['cart' => 'Недостаточно товара «'.$product->name.'» на складе.']);
                }

                $price = (float) ($variant?->final_price ?? $product->final_price);
                $lineTotal = $price * $item->quantity;
                $subtotal += $lineTotal;
                $preparedItems[] = compact('item', 'product', 'variant', 'stock', 'price', 'lineTotal');
            }

            $coupon = $cart->coupon_id ? Coupon::query()->lockForUpdate()->find($cart->coupon_id) : null;
            $discount = (float) ($coupon?->calculateDiscount($subtotal) ?? 0);
            $contact = trim($data['name']).', тел. '.trim($data['phone']).(! empty($data['email']) ? ', '.$data['email'] : '');

            $order = Order::create([
                'order_number' => 'TZ-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'shipping_cost' => 0,
                'discount_amount' => $discount,
                'total' => max(0, $subtotal - $discount),
                'coupon_id' => $discount > 0 ? $coupon?->id : null,
                'customer_note' => $data['customer_note'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $contact,
                'shipping_method' => 'agreed_before_payment',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
            ]);

            foreach ($preparedItems as $prepared) {
                $attributes = $prepared['variant'] ? $prepared['variant']->attributeValues->pluck('value')->implode(', ') : null;
                $order->items()->create([
                    'product_id' => $prepared['product']->id,
                    'variant_id' => $prepared['variant']?->id,
                    'product_name' => $prepared['product']->name,
                    'variant_attributes' => $attributes,
                    'quantity' => $prepared['item']->quantity,
                    'price' => $prepared['price'],
                    'total' => $prepared['lineTotal'],
                ]);
                $prepared['stock']->decrement('quantity', $prepared['item']->quantity);
            }

            $order->addHistory('pending', 'Заказ создан покупателем.');
            if ($discount > 0 && $coupon) {
                $coupon->increment('used_count');
            }
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);

            return $order;
        });

        session()->put('checkout_last_order_id', $order->id);
        $this->sendOrderNotifications($order, $data['email'] ?? null);

        return redirect()->route('checkout.success', $order)->with('success', 'Заказ принят.');
    }

    public function success(Order $order)
    {
        $isOwner = $order->user_id !== null && $order->user_id === Auth::id();
        $isGuestCheckout = $order->user_id === null && (int) session('checkout_last_order_id') === $order->id;
        abort_unless($isOwner || $isGuestCheckout, 403);
        $order->load('items');

        return view('checkout.success', compact('order'));
    }

    private function sendOrderNotifications(Order $order, ?string $customerEmail): void
    {
        $storeEmail = config('store.email');
        $text = "Заказ {$order->order_number}\nСумма товаров: ".number_format((float) $order->total, 0, ',', ' ')." ₽\nСтоимость доставки согласуется отдельно.";

        try {
            if ($customerEmail) {
                Mail::raw($text."\n\nЗаказ принят. Менеджер свяжется с вами для подтверждения.", fn ($message) => $message->to($customerEmail)->subject('Заказ '.$order->order_number));
            }
            if ($storeEmail) {
                Mail::raw($text."\n\nОткройте заказ в административной панели.", fn ($message) => $message->to($storeEmail)->subject('Новый заказ '.$order->order_number));
            }
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
