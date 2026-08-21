<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:10'
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.exists' => 'Выбранный вариант больше недоступен.',
            'quantity.required' => 'Количество обязательно',
            'quantity.min' => 'Минимальное количество: 1',
            'quantity.max' => 'Максимальное количество: 10'
        ];
    }
}
