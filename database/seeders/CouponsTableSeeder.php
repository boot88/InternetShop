<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponsTableSeeder extends Seeder
{
    public function run()
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'description' => 'Скидка 10% для новых клиентов',
                'type' => 'percentage',
                'value' => 10.00,
                'max_discount' => 5000.00,
                'min_order_amount' => 5000.00,
                'max_uses' => 100,
                'used_count' => 25,
                'starts_at' => now()->subDays(30),
                'expires_at' => now()->addDays(30),
                'is_active' => true
            ],
            [
                'code' => 'SUMMER2024',
                'description' => 'Летняя скидка 15% на все товары',
                'type' => 'percentage',
                'value' => 15.00,
                'max_discount' => 10000.00,
                'min_order_amount' => 10000.00,
                'max_uses' => 200,
                'used_count' => 89,
                'starts_at' => now()->subDays(15),
                'expires_at' => now()->addDays(45),
                'is_active' => true
            ],
            [
                'code' => 'FIXED5000',
                'description' => 'Скидка 5000 рублей на заказ от 30000 рублей',
                'type' => 'fixed',
                'value' => 5000.00,
                'max_discount' => 5000.00,
                'min_order_amount' => 30000.00,
                'max_uses' => 50,
                'used_count' => 12,
                'starts_at' => now(),
                'expires_at' => now()->addDays(60),
                'is_active' => true
            ],
            [
                'code' => 'FREE_SHIPPING',
                'description' => 'Бесплатная доставка для заказов от 5000 рублей',
                'type' => 'fixed',
                'value' => 0.00,
                'max_discount' => 1000.00,
                'min_order_amount' => 5000.00,
                'max_uses' => null,
                'used_count' => 156,
                'starts_at' => now()->subDays(60),
                'expires_at' => null,
                'is_active' => true
            ]
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->updateOrInsert(
                ['code' => $coupon['code']],
                array_merge($coupon, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}