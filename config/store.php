<?php

return [
    // Set these values in .env before publishing the shop.
    'phone' => env('STORE_PHONE', '+7 (000) 000-00-00'),
    'email' => env('STORE_EMAIL', 'hello@example.com'),
    'address' => env('STORE_ADDRESS', 'Адрес магазина укажите в настройках'),
    'hours' => env('STORE_HOURS', 'Пн–Вс, 10:00–20:00'),
    'telegram_url' => env('STORE_TELEGRAM_URL'),
    'legal_name' => env('STORE_LEGAL_NAME', 'Реквизиты добавляются владельцем магазина'),
];
