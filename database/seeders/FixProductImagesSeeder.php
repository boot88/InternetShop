<?php
// database/seeders/FixProductImagesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixProductImagesSeeder extends Seeder
{
    public function run()
    {
        // Сначала удаляем дублирующиеся изображения, оставляя только последние
        $this->cleanDuplicateImages();

        // Добавляем недостающие изображения
        $missingImages = [
            // Apple AirPods Pro 2 (product_id = 7)
            [
                'product_id' => 7,
                'image_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Apple AirPods Pro 2',
                'order' => 1
            ],

            // ASUS ROG Strix G18 (product_id = 5)
            [
                'product_id' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'ASUS ROG Strix G18',
                'order' => 1
            ],

            // Canon EOS R6 Mark II (product_id = 8)
            [
                'product_id' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Canon EOS R6 Mark II',
                'order' => 1
            ],

            // Sony WH-1000XM5 (product_id = 9)
            [
                'product_id' => 9,
                'image_path' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'Sony WH-1000XM5',
                'order' => 1
            ],

            // LG OLED 55" C3 (product_id = 10)
            [
                'product_id' => 10,
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'LG OLED 55" C3',
                'order' => 1
            ],

            // TCL C745 65" QLED (product_id = 35 или другой)
            [
                'product_id' => 35, // Проверьте правильный ID
                'image_path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
                'is_main' => true,
                'alt_text' => 'TCL C745 65" QLED',
                'order' => 1
            ],
        ];

        foreach ($missingImages as $image) {
            // Проверяем, существует ли товар и нет ли уже изображения
            $productExists = DB::table('products')->where('id', $image['product_id'])->exists();
            $imageExists = DB::table('product_images')
                ->where('product_id', $image['product_id'])
                ->where('is_main', true)
                ->exists();

            if ($productExists && !$imageExists) {
                DB::table('product_images')->insert(array_merge($image, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Обновляем старые локальные пути на URL
        $this->updateLocalPathsToUrls();
    }

    private function cleanDuplicateImages()
    {
        // Находим товары с дублирующимися изображениями
        $duplicateProducts = DB::table('product_images')
            ->select('product_id', DB::raw('COUNT(*) as count'))
            ->groupBy('product_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicateProducts as $duplicate) {
            // Оставляем только самое последнее изображение для каждого товара
            $latestImage = DB::table('product_images')
                ->where('product_id', $duplicate->product_id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestImage) {
                // Удаляем все остальные изображения для этого товара
                DB::table('product_images')
                    ->where('product_id', $duplicate->product_id)
                    ->where('id', '!=', $latestImage->id)
                    ->delete();
            }
        }
    }

    private function updateLocalPathsToUrls()
    {
        // Обновляем локальные пути на URL для оставшихся изображений
        $localImages = DB::table('product_images')
            ->where('image_path', 'LIKE', 'products/%')
            ->get();

        $urlMappings = [
            'products/iphone-15-pro-max-1.jpg' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop',
            'products/samsung-s23-ultra-1.jpg' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
            'products/xiaomi-13-pro-1.jpg' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&h=600&fit=crop',
            'products/macbook-pro-1.jpg' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop',
            'products/asus-rog-g18-1.jpg' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop',
            'products/samsung-qled-1.jpg' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
            'products/lg-oled-1.jpg' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop',
            'products/canon-r6m2-1.jpg' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
            'products/sony-xm5-1.jpg' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&h=600&fit=crop',
            'products/airpods-pro2-1.jpg' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
        ];

        foreach ($localImages as $image) {
            if (isset($urlMappings[$image->image_path])) {
                DB::table('product_images')
                    ->where('id', $image->id)
                    ->update([
                        'image_path' => $urlMappings[$image->image_path],
                        'updated_at' => now(),
                    ]);
            }
        }
    }
}