<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Смартфоны
            [
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'sku' => 'APPLE-IP15PM-001',
                'description' => 'Флагманский смартфон Apple с процессором A17 Pro, камерой 48 МП и дисплеем 6.7".',
                'short_description' => 'Мощный флагман от Apple',
                'price' => 129999.00,
                'compare_price' => 139999.00,
                'cost' => 100000.00,
                'brand_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.22,
                'dimensions' => '160.9x77.6x8.3 mm'
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra',
                'slug' => 'samsung-galaxy-s23-ultra',
                'sku' => 'SAMSUNG-S23U-001',
                'description' => 'Флагманский смартфон Samsung с S-Pen, камерой 200 МП и экраном 6.8".',
                'short_description' => 'Ультрафлагман с S-Pen',
                'price' => 99999.00,
                'compare_price' => 109999.00,
                'cost' => 80000.00,
                'brand_id' => 2,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.23,
                'dimensions' => '163.4x78.1x8.9 mm'
            ],
            [
                'name' => 'Xiaomi 13 Pro',
                'slug' => 'xiaomi-13-pro',
                'sku' => 'XIAOMI-13P-001',
                'description' => 'Флагманский смартфон Xiaomi с камерой Leica и быстрой зарядкой 120W.',
                'short_description' => 'Флагман с камерой Leica',
                'price' => 79999.00,
                'compare_price' => 89999.00,
                'cost' => 65000.00,
                'brand_id' => 3,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.21,
                'dimensions' => '158.9x73.5x8.4 mm'
            ],
            // Ноутбуки
            [
                'name' => 'MacBook Pro 16" M2 Max',
                'slug' => 'macbook-pro-16-m2-max',
                'sku' => 'APPLE-MBP16-001',
                'description' => 'Мощный ноутбук для профессионалов с процессором M2 Max и дисплеем Liquid Retina XDR.',
                'short_description' => 'Профессиональный ноутбук Apple',
                'price' => 249999.00,
                'compare_price' => 269999.00,
                'cost' => 200000.00,
                'brand_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 2.15,
                'dimensions' => '35.6x24.8x1.7 cm'
            ],
            [
                'name' => 'ASUS ROG Strix G18',
                'slug' => 'asus-rog-strix-g18',
                'sku' => 'ASUS-ROG-G18-001',
                'description' => 'Игровой ноутбук с процессором Intel i9 и видеокартой RTX 4080.',
                'short_description' => 'Мощный игровой ноутбук',
                'price' => 199999.00,
                'compare_price' => 219999.00,
                'cost' => 160000.00,
                'brand_id' => 10,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 3.1,
                'dimensions' => '39.5x29.4x2.3 cm'
            ],
            // Телевизоры
            [
                'name' => 'Samsung QLED 4K 65"',
                'slug' => 'samsung-qled-4k-65',
                'sku' => 'SAMSUNG-Q65-001',
                'description' => 'Телевизор QLED 4K с технологией Quantum HDR и Smart TV.',
                'short_description' => 'Яркий QLED телевизор',
                'price' => 89999.00,
                'compare_price' => 99999.00,
                'cost' => 70000.00,
                'brand_id' => 2,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 25.8,
                'dimensions' => '145.1x83.4x27.2 cm'
            ],
            [
                'name' => 'LG OLED 55" C3',
                'slug' => 'lg-oled-55-c3',
                'sku' => 'LG-OLED55C3-001',
                'description' => 'OLED телевизор с идеальным черным цветом и процессором α9 Gen6.',
                'short_description' => 'Лучший OLED телевизор',
                'price' => 79999.00,
                'compare_price' => 89999.00,
                'cost' => 65000.00,
                'brand_id' => 5,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 18.5,
                'dimensions' => '122.8x71.0x24.1 cm'
            ],
            // Фототехника
            [
                'name' => 'Canon EOS R6 Mark II',
                'slug' => 'canon-eos-r6-mark-ii',
                'sku' => 'CANON-R6M2-001',
                'description' => 'Беззеркальная камера с матрицей 24.2 МП и стабилизацией 8 ступеней.',
                'short_description' => 'Профессиональная беззеркалка',
                'price' => 149999.00,
                'compare_price' => 159999.00,
                'cost' => 120000.00,
                'brand_id' => 15,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.68,
                'dimensions' => '13.8x9.6x8.8 cm'
            ],
            // Наушники
            [
                'name' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'sku' => 'SONY-XM5-001',
                'description' => 'Беспроводные наушники с шумоподавлением и автономностью 30 часов.',
                'short_description' => 'Лучшие наушники с шумоподавлением',
                'price' => 29999.00,
                'compare_price' => 34999.00,
                'cost' => 22000.00,
                'brand_id' => 4,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.25,
                'dimensions' => '19.6x16.8x7.3 cm'
            ],
            [
                'name' => 'Apple AirPods Pro 2',
                'slug' => 'apple-airpods-pro-2',
                'sku' => 'APPLE-AIRPODSP2-001',
                'description' => 'Беспроводные наушники с активным шумоподавлением и зарядкой от MagSafe.',
                'short_description' => 'Премиальные наушники Apple',
                'price' => 24999.00,
                'compare_price' => 27999.00,
                'cost' => 18000.00,
                'brand_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.05,
                'dimensions' => '5.4x2.1x2.1 cm'
            ]
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['slug' => $product['slug']],
                array_merge($product, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}