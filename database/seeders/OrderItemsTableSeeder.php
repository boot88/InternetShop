<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    public function run()
    {
        $orderItems = [
            // Элементы заказа 1
            [
                'order_id' => 1,
                'product_id' => 1,
                'variant_id' => 1,
                'product_name' => 'iPhone 15 Pro Max',
                'variant_attributes' => 'Цвет: Черный, Память: 256 ГБ',
                'quantity' => 1,
                'price' => 129999.00,
                'total' => 129999.00
            ],
            [
                'order_id' => 1,
                'product_id' => 10,
                'variant_id' => 8,
                'product_name' => 'Apple AirPods Pro 2',
                'variant_attributes' => 'Цвет: Белый',
                'quantity' => 1,
                'price' => 24999.00,
                'total' => 24999.00
            ],
            
            // Элементы заказа 2
            [
                'order_id' => 2,
                'product_id' => 2,
                'variant_id' => 4,
                'product_name' => 'Samsung Galaxy S23 Ultra',
                'variant_attributes' => 'Цвет: Черный, Память: 256 ГБ',
                'quantity' => 1,
                'price' => 79999.00,
                'total' => 79999.00
            ]
        ];

        foreach ($orderItems as $item) {
            DB::table('order_items')->insert(array_merge($item, [
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]));
        }
    }
}