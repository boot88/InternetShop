<?php
// database/seeders/ExtendedBrandsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtendedBrandsTableSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Nothing', 'slug' => 'nothing', 'description' => 'Инновационный бренд с уникальным дизайном'],
            ['name' => 'Realme', 'slug' => 'realme', 'description' => 'Молодежный бренд смартфонов и техники'],
            ['name' => 'TCL', 'slug' => 'tcl', 'description' => 'Производитель телевизоров и бытовой техники'],
            ['name' => 'Marshall', 'slug' => 'marshall', 'description' => 'Легендарный бренд аудиотехники'],
            ['name' => 'Sennheiser', 'slug' => 'sennheiser', 'description' => 'Немецкий производитель аудиотехники'],
            ['name' => 'GoPro', 'slug' => 'gopro', 'description' => 'Лидер в производстве экшн-камер'],
            ['name' => 'DJI', 'slug' => 'dji', 'description' => 'Мировой лидер в производстве дронов'],
            ['name' => 'MSI', 'slug' => 'msi', 'description' => 'Производитель игровых ноутбуков и компонентов'],
            ['name' => 'Razer', 'slug' => 'razer', 'description' => 'Игровая периферия и ноутбуки'],
            ['name' => 'HyperX', 'slug' => 'hyperx', 'description' => 'Игровые наушники и аксессуары'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert(array_merge($brand, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}