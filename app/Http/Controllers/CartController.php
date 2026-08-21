<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->items()->with('product.images')->get();

        $total = $cartItems->sum(function ($item) {
            return (int)$item->quantity * (float)$item->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public static function getCartCountStatic()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
        } else {
            $sessionId = session()->getId();
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        return $cart ? (int)$cart->items()->sum('quantity') : 0;
    }

    public function add(Request $request, $productId)
    {
        try {
            $quantity = (int)$request->input('quantity', 1);

            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверное количество'
                ], 422);
            }

            $cart = $this->getOrCreateCart();
            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товар не найден'
                ], 404);
            }

            $existingItem = $cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => (int)$existingItem->quantity + $quantity
                ]);
                $cartItem = $existingItem->fresh();
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => (float)$product->final_price
                ]);
            }

            [$cartCount, $total] = $this->calcCartSummary($cart);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'item_total' => (float)$cartItem->price * (int)$cartItem->quantity,
                'message' => 'Товар "' . $product->name . '" добавлен в корзину'
            ]);

        } catch (\Throwable $e) {
            \Log::error('Cart add error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('id', $itemId)->firstOrFail();

        $cartItem->update([
            'quantity' => (int)$request->quantity
        ]);

        // AJAX / JSON response
        if ($request->expectsJson() || $request->ajax()) {
            $cartItem = $cartItem->fresh();
            [$cartCount, $total] = $this->calcCartSummary($cart);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'item_total' => (float)$cartItem->price * (int)$cartItem->quantity,
                'message' => 'Количество обновлено'
            ]);
        }

        return redirect()->back();
    }

    public function remove(Request $request, $itemId)
    {
        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('id', $itemId)->firstOrFail();
        $cartItem->delete();

        if ($request->expectsJson() || $request->ajax()) {
            [$cartCount, $total] = $this->calcCartSummary($cart);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
                'message' => 'Товар удалён из корзины'
            ]);
        }

        return redirect()->back();
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => 0,
                'total' => 0,
                'message' => 'Корзина очищена'
            ]);
        }

        return redirect()->back();
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'session_id' => session()->getId()
                ]);
            }

            return $cart;
        }

        $sessionId = session()->getId();
        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId
            ]);
        }

        return $cart;
    }

    public function getCartCount()
    {
        $cart = $this->getOrCreateCart();
        return $cart ? (int)$cart->items()->sum('quantity') : 0;
    }

    private function calcCartSummary(Cart $cart): array
    {
        // считаем в PHP — надёжно и без нюансов SQL
        $items = $cart->items()->get(['quantity', 'price']);
        $cartCount = (int)$items->sum('quantity');
        $total = (float)$items->sum(function ($i) {
            return (int)$i->quantity * (float)$i->price;
        });

        return [$cartCount, $total];
    }
}
