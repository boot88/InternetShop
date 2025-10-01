<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderHistoriesTableSeeder extends Seeder
{
    public function run()
    {
        $histories = [
            // История заказа 1
            [
                'order_id' => 1,
                'status' => 'pending',
                'note' => 'Заказ создан',
                'created_at' => now()->subDays(10)
            ],
            [
                'order_id' => 1,
                'status' => 'processing',
                'note' => 'Заказ подтвержден, готовится к отправке',
                'created_at' => now()->subDays(9)
            ],
            [
                'order_id' => 1,
                'status' => 'shipped',
                'note' => 'Заказ передан в службу доставки',
                'created_at' => now()->subDays(7)
            ],
            [
                'order_id' => 1,
                'status' => 'delivered',
                'note' => 'Заказ доставлен покупателю',
                'created_at' => now()->subDays(5)
            ],
            
            // История заказа 2
            [
                'order_id' => 2,
                'status' => 'pending',
                'note' => 'Заказ создан',
                'created_at' => now()->subDays(2)
            ],
            [
                'order_id' => 2,
                'status' => 'processing',
                'note' => 'Заказ в обработке',
                'created_at' => now()->subDays(1)
            ]
        ];

        foreach ($histories as $history) {
            DB::table('order_histories')->insert($history);
        }
    }
}