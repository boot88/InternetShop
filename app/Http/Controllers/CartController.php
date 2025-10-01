<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->items()->with('product.images')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(AddToCartRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getOrCreateCart();

        // Проверяем, есть ли уже этот товар в корзине
        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'price' => $product->final_price
            ]);
        }

        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Количество обновлено');
    }

    public function remove($itemId)
    {
        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->back()->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return redirect()->back()->with('success', 'Корзина очищена');
    }

    private function getOrCreateCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);
        }

        return $cart;
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return 0;
        }

        return $cart->items()->sum('quantity');
    }
}