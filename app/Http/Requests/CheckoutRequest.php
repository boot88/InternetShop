<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:7', 'max:30', 'regex:/^[+0-9()\-\s]+$/'],
            'shipping_address' => ['required', 'string', 'min:8', 'max:1000'],
            'payment_method' => ['required', 'in:card_on_delivery,bank_transfer'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'privacy_consent' => ['accepted'],
            'terms_consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'privacy_consent.accepted' => 'Необходимо согласие на обработку персональных данных.',
            'terms_consent.accepted' => 'Необходимо принять условия оформления заказа.',
            'phone.regex' => 'Укажите телефон только цифрами и допустимыми разделителями.',
        ];
    }
}
