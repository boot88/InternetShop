<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'nullable|exists:categories,id',
            'brand' => 'nullable|exists:brands,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'search' => 'nullable|string|max:255',
            'sort' => 'nullable|in:name_asc,name_desc,price_asc,price_desc,newest',
            'page' => 'nullable|integer|min:1'
        ];
    }
}