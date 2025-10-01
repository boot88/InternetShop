<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $orders = [
            [
                'order_number' => 'ORD-' . now()->format('Ymd') . '-0001',
                'user_id' => 2,
                'status' => 'delivered',
                'subtotal' => 154998.00,
                'tax_amount' => 18599.76,
                'shipping_cost' => 0.00,
                'discount_amount' => 0.00,
                'total' => 173597.76,
                'coupon_id' => null,
                'customer_note' => 'Пожалуйста, позвонить за час до доставки',
                'shipping_address' => 'Москва, ул. Ленина, д. 25, кв. 12',
                'billing_address' => 'Москва, ул. Ленина, д. 25, кв. 12',
                'shipping_method' => 'курьер',
                'payment_method' => 'карта',
                'payment_status' => 'paid',
                'transaction_id' => 'TXN' . now()->format('YmdHis') . '001'
            ],
            [
                'order_number' => 'ORD-' . now()->format('Ymd') . '-0002',
                'user_id' => 3,
                'status' => 'processing',
                'subtotal' => 79999.00,
                'tax_amount' => 9599.88,
                'shipping_cost' => 500.00,
                'discount_amount' => 0.00,
                'total' => 90098.88,
                'coupon_id' => null,
                'customer_note' => '',
                'shipping_address' => 'Санкт-Петербург, Невский пр., д. 100',
                'billing_address' => 'Санкт-Петербург, Невский пр., д. 100',
                'shipping_method' => 'почта',
                'payment_method' => 'карта',
                'payment_status' => 'paid',
                'transaction_id' => 'TXN' . now()->format('YmdHis') . '002'
            ]
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert(array_merge($order, [
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]));
        }
    }
}