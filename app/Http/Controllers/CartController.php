<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
  
    
	public static function getCartCountStatic()
{
    if (Auth::check()) {
        $cart = Cart::where('user_id', Auth::id())->first();
    } else {
        $sessionId = session()->getId();
        $cart = Cart::where('session_id', $sessionId)->first();
    }
    
    return $cart ? $cart->items()->sum('quantity') : 0;
}
   
  
public function add(Request $request, $productId)
{
    try {
        \Log::info('Cart add with ID - Product ID: ' . $productId);
        \Log::info('Request data:', $request->all());

        $quantity = $request->input('quantity', 1);

        if ($quantity < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Неверное количество'
            ], 422);
        }

        $cart = $this->getOrCreateCart();
        $product = Product::find($productId);

        if (!$product) {
            \Log::error('Product not found with ID: ' . $productId);
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
                'quantity' => $existingItem->quantity + $quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->final_price
            ]);
        }

        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'message' => 'Товар "' . $product->name . '" добавлен в корзину'
        ]);

    } catch (\Exception $e) {
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
        'quantity' => $request->quantity
    ]);

    return redirect()->back();//->with('success', 'Количество обновлено');
}

    public function remove($itemId)
{
    $cart = $this->getOrCreateCart();
    
    $cartItem = $cart->items()->where('id', $itemId)->firstOrFail();

    $cartItem->delete();

    return redirect()->back();//->with('success', 'Товар удален из корзины');
}

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return redirect()->back();//->with('success', 'Корзина очищена');
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

    // Для гостей
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
    
    if (!$cart) {
        return 0;
    }

    return $cart->items()->sum('quantity');
}
}