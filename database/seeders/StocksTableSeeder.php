<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksTableSeeder extends Seeder
{
    public function run()
    {
        $stocks = [
            // Вариации iPhone
            ['variant_id' => 1, 'quantity' => 15, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 2, 'quantity' => 8, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 3, 'quantity' => 12, 'low_stock_threshold' => 5, 'location' => 'Санкт-Петербург'],
            
            // Вариации Samsung
            ['variant_id' => 4, 'quantity' => 20, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 5, 'quantity' => 6, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            
            // Ноутбуки
            ['variant_id' => 6, 'quantity' => 10, 'low_stock_threshold' => 3, 'location' => 'Москва'],
            ['variant_id' => 7, 'quantity' => 4, 'low_stock_threshold' => 3, 'location' => 'Санкт-Петербург'],
            
            // Наушники
            ['variant_id' => 8, 'quantity' => 25, 'low_stock_threshold' => 10, 'location' => 'Москва'],
            
            // Товары без вариаций
            ['product_id' => 6, 'quantity' => 7, 'low_stock_threshold' => 3, 'location' => 'Москва'],
            ['product_id' => 7, 'quantity' => 9, 'low_stock_threshold' => 3, 'location' => 'Санкт-Петербург'],
            ['product_id' => 8, 'quantity' => 5, 'low_stock_threshold' => 2, 'location' => 'Москва'],
            ['product_id' => 9, 'quantity' => 18, 'low_stock_threshold' => 5, 'location' => 'Москва'],
        ];

        foreach ($stocks as $stock) {
            DB::table('stocks')->insert(array_merge($stock, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}