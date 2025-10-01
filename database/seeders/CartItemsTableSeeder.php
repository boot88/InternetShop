<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsTableSeeder extends Seeder
{
    public function run()
    {
        $cartItems = [
            // Корзина пользователя 2
            [
                'cart_id' => 1,
                'product_id' => 1, // iPhone 15 Pro Max
                'variant_id' => 1, // 256GB Black
                'quantity' => 1,
                'price' => 129999.00
            ],
            [
                'cart_id' => 1,
                'product_id' => 10, // AirPods Pro 2
                'variant_id' => 8, // White
                'quantity' => 1,
                'price' => 24999.00
            ],
            
            // Корзина пользователя 3
            [
                'cart_id' => 2,
                'product_id' => 2, // Samsung S23 Ultra
                'variant_id' => 4, // 256GB Black
                'quantity' => 1,
                'price' => 99999.00
            ],
            
            // Корзина гостя
            [
                'cart_id' => 3,
                'product_id' => 9, // Sony WH-1000XM5
                'variant_id' => null,
                'quantity' => 1,
                'price' => 29999.00
            ]
        ];

        foreach ($cartItems as $item) {
            DB::table('cart_items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}