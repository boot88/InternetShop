<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductTableSeeder extends Seeder
{
    public function run()
    {
        $categoryProducts = [
            // iPhone 15 Pro Max → Apple iPhone
            ['category_id' => 7, 'product_id' => 1],
            
            // Samsung Galaxy S23 Ultra → Samsung Galaxy
            ['category_id' => 8, 'product_id' => 2],
            
            // Xiaomi 13 Pro → Xiaomi
            ['category_id' => 9, 'product_id' => 3],
            
            // MacBook Pro → Ноутбуки Apple
            ['category_id' => 13, 'product_id' => 4],
            
            // ASUS ROG Strix G18 → Игровые ноутбуки
            ['category_id' => 11, 'product_id' => 5],
            
            // Samsung QLED TV → 4K телевизоры
            ['category_id' => 16, 'product_id' => 6],
            
            // LG OLED TV → OLED телевизоры
            ['category_id' => 18, 'product_id' => 7],
            
            // Canon EOS R6 Mark II → Беззеркальные камеры
            ['category_id' => 22, 'product_id' => 8],
            
            // Sony WH-1000XM5 → Наушники
            ['category_id' => 20, 'product_id' => 9],
            
            // Apple AirPods Pro 2 → Наушники
            ['category_id' => 20, 'product_id' => 10],
            
            // Дополнительные связи для лучшей навигации
            // Смартфоны также в основной категории Смартфоны
            ['category_id' => 1, 'product_id' => 1],
            ['category_id' => 1, 'product_id' => 2],
            ['category_id' => 1, 'product_id' => 3],
            
            // Ноутбуки в основной категории Ноутбуки
            ['category_id' => 2, 'product_id' => 4],
            ['category_id' => 2, 'product_id' => 5],
            
            // Телевизоры в основной категории Телевизоры
            ['category_id' => 3, 'product_id' => 6],
            ['category_id' => 3, 'product_id' => 7],
            
            // Фототехника в основной категории
            ['category_id' => 4, 'product_id' => 8],
            
            // Наушники в основной категории Телевизоры и аудио
            ['category_id' => 3, 'product_id' => 9],
            ['category_id' => 3, 'product_id' => 10],
        ];

        foreach ($categoryProducts as $cp) {
            DB::table('category_product')->updateOrInsert(
                ['category_id' => $cp['category_id'], 'product_id' => $cp['product_id']],
                array_merge($cp, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}