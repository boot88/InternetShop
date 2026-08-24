<?php

return [
    'name' => env('STORE_NAME', 'TechZone'),
    'phone' => env('STORE_PHONE'),
    'email' => env('STORE_EMAIL'),
    'address' => env('STORE_ADDRESS'),
    'hours' => env('STORE_HOURS'),
    'telegram_url' => env('STORE_TELEGRAM_URL'),
    'legal_name' => env('STORE_LEGAL_NAME'),
    'inn' => env('STORE_INN'),
    'ogrn' => env('STORE_OGRN'),
    'legal_address' => env('STORE_LEGAL_ADDRESS'),
    'return_address' => env('STORE_RETURN_ADDRESS'),
    'bank_details' => env('STORE_BANK_DETAILS'),
    'delivery_note' => env('STORE_DELIVERY_NOTE', 'Срок и стоимость доставки согласуются до оплаты заказа.'),
    'warranty_note' => env('STORE_WARRANTY_NOTE', 'Гарантийный срок указан в документах конкретного товара.'),
    'analytics_id' => env('YANDEX_METRIKA_ID'),
    'admin_emails' => array_values(array_filter(array_map('trim', explode(',', (string) env('STORE_ADMIN_EMAILS', ''))))),
];
