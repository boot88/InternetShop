<?php
// database/seeders/ExtendedProductImagesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtendedProductImagesTableSeeder extends Seeder
{
    public function run()
    {
        $images = [
            // iPhone 15 Pro Max
            [
                'product_id' => 1, // Предполагаемый ID для iPhone 15 Pro Max
                'image_path' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'iPhone 15 Pro Max',
                'order' => 1
            ],

            // Samsung Galaxy S23 Ultra
            [
                'product_id' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Samsung Galaxy S23 Ultra',
                'order' => 1
            ],

            // Xiaomi 13 Pro
            [
                'product_id' => 3,
                'image_path' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Xiaomi 13 Pro',
                'order' => 1
            ],

            // MacBook Pro 16" M2 Max
            [
                'product_id' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'MacBook Pro 16" M2 Max',
                'order' => 1
            ],

            // ASUS ROG Strix G18
            [
                'product_id' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'ASUS ROG Strix G18',
                'order' => 1
            ],

            // Samsung QLED 4K 65"
            [
                'product_id' => 6,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Samsung QLED 4K 65"',
                'order' => 1
            ],

            // Apple AirPods Pro 2
            [
                'product_id' => 7,
                'image_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Apple AirPods Pro 2',
                'order' => 1
            ],

            // Canon EOS R6 Mark II
            [
                'product_id' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Canon EOS R6 Mark II',
                'order' => 1
            ],

            // Sony WH-1000XM5
            [
                'product_id' => 9,
                'image_path' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Sony WH-1000XM5',
                'order' => 1
            ],

            // LG OLED 55" C3
            [
                'product_id' => 10,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'LG OLED 55" C3',
                'order' => 1
            ],

            // iPhone 15 Pro
            [
                'product_id' => 11,
                'image_path' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'iPhone 15 Pro Natural Titanium',
                'order' => 1
            ],
            [
                'product_id' => 11,
                'image_path' => 'https://images.unsplash.com/photo-1695048133148-1c12faba6c78?w=600&h=600&fit=crop',
                'is_main' => false,
                'alt_text' => 'iPhone 15 Pro Blue Titanium',
                'order' => 2
            ],

            // Samsung Z Flip5
            [
                'product_id' => 12,
                'image_path' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Samsung Galaxy Z Flip5',
                'order' => 1
            ],

            // Google Pixel 8 Pro
            [
                'product_id' => 13,
                'image_path' => 'https://images.unsplash.com/photo-1695048143090-1f2fce4c5a8c?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Google Pixel 8 Pro',
                'order' => 1
            ],

            // OnePlus 11
            [
                'product_id' => 14,
                'image_path' => 'https://images.unsplash.com/photo-1598301257982-0cf01499abb2?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'OnePlus 11 5G',
                'order' => 1
            ],

            // Xiaomi Redmi Note 13 Pro
            [
                'product_id' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Xiaomi Redmi Note 13 Pro',
                'order' => 1
            ],

            // Realme GT Neo 5
            [
                'product_id' => 16,
                'image_path' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Realme GT Neo 5',
                'order' => 1
            ],

            // Nothing Phone (2)
            [
                'product_id' => 17,
                'image_path' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Nothing Phone (2)',
                'order' => 1
            ],

            // ASUS ROG Phone 7
            [
                'product_id' => 18,
                'image_path' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'ASUS ROG Phone 7',
                'order' => 1
            ],

            // MacBook Air M2
            [
                'product_id' => 19,
                'image_path' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'MacBook Air 13" M2',
                'order' => 1
            ],

            // Dell XPS 13 Plus
            [
                'product_id' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Dell XPS 13 Plus',
                'order' => 1
            ],

            // Lenovo Yoga 9i
            [
                'product_id' => 21,
                'image_path' => 'https://images.unsplash.com/photo-1587614382346-4ec70e388b28?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Lenovo Yoga 9i',
                'order' => 1
            ],

            // HP Spectre x360
            [
                'product_id' => 22,
                'image_path' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'HP Spectre x360',
                'order' => 1
            ],

            // ASUS Zenbook 14X
            [
                'product_id' => 23,
                'image_path' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'ASUS Zenbook 14X',
                'order' => 1
            ],

            // Sony Bravia XR A95L 65"
            [
                'product_id' => 24,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Sony Bravia XR A95L 65"',
                'order' => 1
            ],

            // LG G3 77" OLED
            [
                'product_id' => 25,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'LG G3 77" OLED',
                'order' => 1
            ],

            // Samsung The Frame 55"
            [
                'product_id' => 26,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Samsung The Frame 55"',
                'order' => 1
            ],

            // Sony WH-1000XM4
            [
                'product_id' => 27,
                'image_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Sony WH-1000XM4',
                'order' => 1
            ],

            // Bose QuietComfort Ultra
            [
                'product_id' => 28,
                'image_path' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Bose QuietComfort Ultra',
                'order' => 1
            ],

            // JBL Flip 6
            [
                'product_id' => 29,
                'image_path' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'JBL Flip 6',
                'order' => 1
            ],

            // Marshall Stanmore III
            [
                'product_id' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Marshall Stanmore III',
                'order' => 1
            ],

            // Nikon Z9
            [
                'product_id' => 31,
                'image_path' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Nikon Z9',
                'order' => 1
            ],

            // Canon RF 24-70mm f/2.8L
            [
                'product_id' => 32,
                'image_path' => 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Canon RF 24-70mm f/2.8L',
                'order' => 1
            ],

            // GoPro HERO12 Black
            [
                'product_id' => 33,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'GoPro HERO12 Black',
                'order' => 1
            ],

            // DJI Mini 4 Pro
            [
                'product_id' => 34,
                'image_path' => 'https://images.unsplash.com/photo-1579829366248-204fe8413f31?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'DJI Mini 4 Pro',
                'order' => 1
            ],

            // TCL C745 65" QLED
            [
                'product_id' => 35, // Предполагаемый ID для TCL
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'TCL C745 65" QLED',
                'order' => 1
            ],
        ];

        foreach ($images as $image) {
            // Проверяем, существует ли уже изображение для этого товара
            $existingImage = DB::table('product_images')
                ->where('product_id', $image['product_id'])
                ->where('is_main', true)
                ->first();
            
            if (!$existingImage) {
                DB::table('product_images')->insert(array_merge($image, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}