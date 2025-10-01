<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValuesTableSeeder extends Seeder
{
    public function run()
    {
        $values = [
            // Цвета (attribute_id = 1)
            ['attribute_id' => 1, 'value' => 'Черный', 'color_code' => '#000000'],
            ['attribute_id' => 1, 'value' => 'Белый', 'color_code' => '#FFFFFF'],
            ['attribute_id' => 1, 'value' => 'Серебристый', 'color_code' => '#C0C0C0'],
            ['attribute_id' => 1, 'value' => 'Золотой', 'color_code' => '#FFD700'],
            ['attribute_id' => 1, 'value' => 'Синий', 'color_code' => '#0000FF'],
            ['attribute_id' => 1, 'value' => 'Красный', 'color_code' => '#FF0000'],
            ['attribute_id' => 1, 'value' => 'Зеленый', 'color_code' => '#008000'],
            ['attribute_id' => 1, 'value' => 'Фиолетовый', 'color_code' => '#800080'],
            
            // Размеры экрана (attribute_id = 2)
            ['attribute_id' => 2, 'value' => '5.5"'],
            ['attribute_id' => 2, 'value' => '6.1"'],
            ['attribute_id' => 2, 'value' => '6.7"'],
            ['attribute_id' => 2, 'value' => '6.9"'],
            ['attribute_id' => 2, 'value' => '7.2"'],
            
            // Объем памяти (attribute_id = 3)
            ['attribute_id' => 3, 'value' => '64 ГБ'],
            ['attribute_id' => 3, 'value' => '128 ГБ'],
            ['attribute_id' => 3, 'value' => '256 ГБ'],
            ['attribute_id' => 3, 'value' => '512 ГБ'],
            ['attribute_id' => 3, 'value' => '1 ТБ'],
            
            // Оперативная память (attribute_id = 4)
            ['attribute_id' => 4, 'value' => '4 ГБ'],
            ['attribute_id' => 4, 'value' => '6 ГБ'],
            ['attribute_id' => 4, 'value' => '8 ГБ'],
            ['attribute_id' => 4, 'value' => '12 ГБ'],
            ['attribute_id' => 4, 'value' => '16 ГБ'],
            
            // Процессоры (attribute_id = 5)
            ['attribute_id' => 5, 'value' => 'Apple A15 Bionic'],
            ['attribute_id' => 5, 'value' => 'Apple A16 Bionic'],
            ['attribute_id' => 5, 'value' => 'Snapdragon 8 Gen 2'],
            ['attribute_id' => 5, 'value' => 'Snapdragon 8 Gen 3'],
            ['attribute_id' => 5, 'value' => 'Exynos 2200'],
            ['attribute_id' => 5, 'value' => 'MediaTek Dimensity 9000'],
            
            // Диагонали телевизоров (attribute_id = 8)
            ['attribute_id' => 8, 'value' => '43"'],
            ['attribute_id' => 8, 'value' => '50"'],
            ['attribute_id' => 8, 'value' => '55"'],
            ['attribute_id' => 8, 'value' => '65"'],
            ['attribute_id' => 8, 'value' => '75"'],
            ['attribute_id' => 8, 'value' => '85"'],
            
            // Разрешения (attribute_id = 9)
            ['attribute_id' => 9, 'value' => 'Full HD'],
            ['attribute_id' => 9, 'value' => '2K'],
            ['attribute_id' => 9, 'value' => '4K'],
            ['attribute_id' => 9, 'value' => '8K'],
        ];

        foreach ($values as $value) {
            DB::table('attribute_values')->updateOrInsert(
                ['attribute_id' => $value['attribute_id'], 'value' => $value['value']],
                array_merge($value, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}