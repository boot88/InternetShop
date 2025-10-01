<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        $reviews = [
            // Отзывы на iPhone 15 Pro Max
            [
                'product_id' => 1,
                'user_id' => 2,
                'rating' => 5,
                'title' => 'Отличный флагман!',
                'comment' => 'Очень доволен покупкой. Камера просто супер, батарея держит долго.',
                'is_approved' => true
            ],
            [
                'product_id' => 1,
                'user_id' => 3,
                'rating' => 4,
                'title' => 'Хороший телефон',
                'comment' => 'В целом отличный аппарат, но цена завышена.',
                'is_approved' => true
            ],
            
            // Отзывы на Samsung Galaxy S23 Ultra
            [
                'product_id' => 2,
                'user_id' => 4,
                'rating' => 5,
                'title' => 'Лучший Android-смартфон',
                'comment' => 'S-Pen очень удобен для работы. Камера 200 МП впечатляет.',
                'is_approved' => true
            ],
            
            // Отзывы на MacBook Pro
            [
                'product_id' => 4,
                'user_id' => 5,
                'rating' => 5,
                'title' => 'Мощная машина',
                'comment' => 'Идеально для работы с видео и графикой. Батареи хватает на весь день.',
                'is_approved' => true
            ],
            
            // Отзывы на Sony WH-1000XM5
            [
                'product_id' => 9,
                'user_id' => 2,
                'rating' => 5,
                'title' => 'Лучшие наушники с шумоподавлением',
                'comment' => 'Шумоподавление на высоте, звук чистый и детальный.',
                'is_approved' => true
            ],
            
            // Отзывы на AirPods Pro 2
            [
                'product_id' => 10,
                'user_id' => 3,
                'rating' => 4,
                'title' => 'Удобные наушники',
                'comment' => 'Отлично сидят в ушах, звук хороший, но дороговаты.',
                'is_approved' => true
            ]
        ];

        foreach ($reviews as $review) {
            DB::table('reviews')->insert(array_merge($review, [
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 60)),
            ]));
        }
    }
}