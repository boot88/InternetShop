<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
	public function add(AddToCartRequest $request)
{
    $product = Product::findOrFail($request->product_id);
    $cart = $this->getOrCreateCart();

    // Проверяем, есть ли уже этот товар в корзине
    $existingItem = $cart->items()
        ->where('product_id', $request->product_id)
        ->where('variant_id', $request->variant_id)
        ->first();

    if ($existingItem) {
        $existingItem->update([
            'quantity' => $existingItem->quantity + $request->quantity
        ]);
    } else {
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'quantity' => $request->quantity,
            'price' => $product->final_price
        ]);
    }

    return redirect()->back()->with('success', 'Товар добавлен в корзину');
}
	
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
			'product_variant_id' => 'sometimes|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:10'
        ];
    }
	
	public function messages()
    {
        return [
            'product_id.required_without' => 'Необходимо указать товар или вариант товара',
            'variant_id.required_without' => 'Необходимо указать вариант товара или товар',
            'quantity.required' => 'Количество обязательно',
            'quantity.min' => 'Минимальное количество: 1',
            'quantity.max' => 'Максимальное количество: 10'
        ];
    }
	
}