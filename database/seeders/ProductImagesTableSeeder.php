<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesTableSeeder extends Seeder
{
    public function run()
    {
        $images = [
            // iPhone 15 Pro Max
            [
                'product_id' => 1,
                'image_path' => 'products/iphone-15-pro-max-1.jpg',
                'is_main' => true,
                'alt_text' => 'iPhone 15 Pro Max - основной вид',
                'order' => 1
            ],
            [
                'product_id' => 1,
                'image_path' => 'products/iphone-15-pro-max-2.jpg',
                'is_main' => false,
                'alt_text' => 'iPhone 15 Pro Max - задняя панель',
                'order' => 2
            ],
            
            // Samsung Galaxy S23 Ultra
            [
                'product_id' => 2,
                'image_path' => 'products/samsung-s23-ultra-1.jpg',
                'is_main' => true,
                'alt_text' => 'Samsung Galaxy S23 Ultra - основной вид',
                'order' => 1
            ],
            
            // Xiaomi 13 Pro
            [
                'product_id' => 3,
                'image_path' => 'products/xiaomi-13-pro-1.jpg',
                'is_main' => true,
                'alt_text' => 'Xiaomi 13 Pro - основной вид',
                'order' => 1
            ],
            
            // MacBook Pro
            [
                'product_id' => 4,
                'image_path' => 'products/macbook-pro-1.jpg',
                'is_main' => true,
                'alt_text' => 'MacBook Pro 16" - основной вид',
                'order' => 1
            ],
            
            // ASUS ROG Strix G18
            [
                'product_id' => 5,
                'image_path' => 'products/asus-rog-g18-1.jpg',
                'is_main' => true,
                'alt_text' => 'ASUS ROG Strix G18 - игровой ноутбук',
                'order' => 1
            ],
            
            // Samsung QLED TV
            [
                'product_id' => 6,
                'image_path' => 'products/samsung-qled-1.jpg',
                'is_main' => true,
                'alt_text' => 'Samsung QLED 4K 65" - телевизор',
                'order' => 1
            ],
            
            // LG OLED TV
            [
                'product_id' => 7,
                'image_path' => 'products/lg-oled-1.jpg',
                'is_main' => true,
                'alt_text' => 'LG OLED 55" C3 - телевизор',
                'order' => 1
            ],
            
            // Canon EOS R6 Mark II
            [
                'product_id' => 8,
                'image_path' => 'products/canon-r6m2-1.jpg',
                'is_main' => true,
                'alt_text' => 'Canon EOS R6 Mark II - фотоаппарат',
                'order' => 1
            ],
            
            // Sony WH-1000XM5
            [
                'product_id' => 9,
                'image_path' => 'products/sony-xm5-1.jpg',
                'is_main' => true,
                'alt_text' => 'Sony WH-1000XM5 - наушники',
                'order' => 1
            ],
            
            // Apple AirPods Pro 2
            [
                'product_id' => 10,
                'image_path' => 'products/airpods-pro2-1.jpg',
                'is_main' => true,
                'alt_text' => 'Apple AirPods Pro 2 - наушники',
                'order' => 1
            ]
        ];

        foreach ($images as $image) {
            DB::table('product_images')->insert(array_merge($image, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}