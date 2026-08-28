<?php

return [
    'name' => env('STORE_NAME', 'TechZone'),
    'phone' => env('STORE_PHONE', '+7 (913) 008-01-46'),
    'email' => env('STORE_EMAIL', 'manager@happypils.ru'),
    'address' => env('STORE_ADDRESS', '630007, Новосибирская область, г. Новосибирск, ул. Ленина, д. 2, офис 303'),
    'hours' => env('STORE_HOURS', 'Пн–Пт, 08:00–19:00 (новосибирское время)'),
    'telegram_url' => env('STORE_TELEGRAM_URL'),
    'legal_name' => env('STORE_LEGAL_NAME', 'ООО «ТехноЗона»'),
    'inn' => env('STORE_INN', '5415255122'),
    'ogrn' => env('STORE_OGRN', '1065401531135'),
    'legal_address' => env('STORE_LEGAL_ADDRESS', '630007, Новосибирская область, г. Новосибирск, ул. Ленина, д. 56, офис 234'),
    'return_address' => env('STORE_RETURN_ADDRESS', 'Новосибирская область, г. Новосибирск, ул. Ленина, д. 33, офис 102'),
    'bank_details' => env('STORE_BANK_DETAILS'),
    'delivery_note' => env('STORE_DELIVERY_NOTE', 'Самовывоз, СДЭК или Почта России: срок и стоимость подтверждаются менеджером до предоплаты.'),
    'warranty_note' => env('STORE_WARRANTY_NOTE', 'Гарантийный срок и порядок сервиса указаны в документах конкретного товара. При обращении подготовьте номер заказа.'),
    'analytics_id' => env('YANDEX_METRIKA_ID'),
    'notification_emails' => array_values(array_unique(array_filter(array_map(
        static fn ($email) => strtolower(trim($email)),
        explode(',', (string) env('STORE_NOTIFICATION_EMAILS', 'manager@happypils.ru,povisok888@gmail.com'))
    ), static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false))),
    'admin_emails' => array_values(array_unique(array_filter(array_map(
        static fn ($email) => strtolower(trim($email)),
        explode(',', (string) env('STORE_ADMIN_EMAILS', 'manager@happypils.ru'))
    ), static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false))),
];
