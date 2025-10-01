<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'Техника Apple - инновационные продукты высшего качества'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'Samsung - корейский гигант электроники'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'description' => 'Xiaomi - качественная техника по доступным ценам'],
            ['name' => 'Sony', 'slug' => 'sony', 'description' => 'Sony - японское качество и надежность'],
            ['name' => 'LG', 'slug' => 'lg', 'description' => 'LG - инновационные решения для дома'],
            ['name' => 'Huawei', 'slug' => 'huawei', 'description' => 'Huawei - китайский технологический лидер'],
            ['name' => 'Google', 'slug' => 'google', 'description' => 'Google - техника с чистым Android'],
            ['name' => 'OnePlus', 'slug' => 'oneplus', 'description' => 'OnePlus - флагманские характеристики'],
            ['name' => 'Nokia', 'slug' => 'nokia', 'description' => 'Nokia - надежность проверенная временем'],
            ['name' => 'ASUS', 'slug' => 'asus', 'description' => 'ASUS - геймерская техника и ноутбуки'],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'description' => 'Lenovo - качественные ноутбуки и компьютеры'],
            ['name' => 'Acer', 'slug' => 'acer', 'description' => 'Acer - доступная техника для всех'],
            ['name' => 'Dell', 'slug' => 'dell', 'description' => 'Dell - бизнес-решения и ноутбуки'],
            ['name' => 'HP', 'slug' => 'hp', 'description' => 'HP - надежная компьютерная техника'],
            ['name' => 'Canon', 'slug' => 'canon', 'description' => 'Canon - фототехника и принтеры'],
            ['name' => 'Nikon', 'slug' => 'nikon', 'description' => 'Nikon - профессиональная фототехника'],
            ['name' => 'Bose', 'slug' => 'bose', 'description' => 'Bose - премиальные аудиосистемы'],
            ['name' => 'JBL', 'slug' => 'jbl', 'description' => 'JBL - качественный звук для всех'],
            ['name' => 'Philips', 'slug' => 'philips', 'description' => 'Philips - техника для дома'],
            ['name' => 'Braun', 'slug' => 'braun', 'description' => 'Braun - немецкое качество бытовой техники'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->updateOrInsert(
                ['slug' => $brand['slug']],
                array_merge($brand, [
				    'description' => 'Описание бренда ' . $brand['name'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}