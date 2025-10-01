<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributesTableSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            ['name' => 'Цвет', 'slug' => 'color', 'type' => 'color', 'is_filterable' => true],
            ['name' => 'Размер экрана', 'slug' => 'screen-size', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Объем памяти', 'slug' => 'storage', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Оперативная память', 'slug' => 'ram', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Процессор', 'slug' => 'processor', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Размер', 'slug' => 'size', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Материал', 'slug' => 'material', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Диагональ телевизора', 'slug' => 'tv-size', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Разрешение', 'slug' => 'resolution', 'type' => 'select', 'is_filterable' => true],
            ['name' => 'Емкость аккумулятора', 'slug' => 'battery', 'type' => 'select', 'is_filterable' => true],
        ];

        foreach ($attributes as $attribute) {
            DB::table('attributes')->updateOrInsert(
                ['slug' => $attribute['slug']],
                array_merge($attribute, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}