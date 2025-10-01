<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Основные категории (parent_id = null)
            ['name' => 'Смартфоны и гаджеты', 'slug' => 'smartphones', 'parent_id' => null],
            ['name' => 'Ноутбуки и компьютеры', 'slug' => 'computers', 'parent_id' => null],
            ['name' => 'Телевизоры и аудио', 'slug' => 'tv-audio', 'parent_id' => null],
            ['name' => 'Фототехника', 'slug' => 'photo', 'parent_id' => null],
            ['name' => 'Бытовая техника', 'slug' => 'home-appliances', 'parent_id' => null],
            ['name' => 'Аксессуары', 'slug' => 'accessories', 'parent_id' => null],
            
            // Подкатегории для Смартфонов
            ['name' => 'Apple iPhone', 'slug' => 'apple-iphone', 'parent_id' => 1],
            ['name' => 'Samsung Galaxy', 'slug' => 'samsung-galaxy', 'parent_id' => 1],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi-phones', 'parent_id' => 1],
            ['name' => 'Игровые смартфоны', 'slug' => 'gaming-phones', 'parent_id' => 1],
            ['name' => 'Бюджетные смартфоны', 'slug' => 'budget-phones', 'parent_id' => 1],
            
            // Подкатегории для Ноутбуков
            ['name' => 'Игровые ноутбуки', 'slug' => 'gaming-laptops', 'parent_id' => 2],
            ['name' => 'Ультрабуки', 'slug' => 'ultrabooks', 'parent_id' => 2],
            ['name' => 'Ноутбуки Apple', 'slug' => 'apple-laptops', 'parent_id' => 2],
            ['name' => 'Ноутбуки для работы', 'slug' => 'business-laptops', 'parent_id' => 2],
            ['name' => 'Моноблоки', 'slug' => 'all-in-one', 'parent_id' => 2],
            
            // Подкатегории для Телевизоров
            ['name' => '4K телевизоры', 'slug' => '4k-tv', 'parent_id' => 3],
            ['name' => 'Smart TV', 'slug' => 'smart-tv', 'parent_id' => 3],
            ['name' => 'OLED телевизоры', 'slug' => 'oled-tv', 'parent_id' => 3],
            ['name' => 'Акустические системы', 'slug' => 'audio-systems', 'parent_id' => 3],
            ['name' => 'Наушники', 'slug' => 'headphones', 'parent_id' => 3],
            
            // Подкатегории для Фототехники
            ['name' => 'Зеркальные фотоаппараты', 'slug' => 'dslr', 'parent_id' => 4],
            ['name' => 'Беззеркальные камеры', 'slug' => 'mirrorless', 'parent_id' => 4],
            ['name' => 'Объективы', 'slug' => 'lenses', 'parent_id' => 4],
            ['name' => 'Экшн-камеры', 'slug' => 'action-cameras', 'parent_id' => 4],
            
            // Подкатегории для Бытовая техника
            ['name' => 'Холодильники', 'slug' => 'refrigerators', 'parent_id' => 5],
            ['name' => 'Стиральные машины', 'slug' => 'washing-machines', 'parent_id' => 5],
            ['name' => 'Кофемашины', 'slug' => 'coffee-machines', 'parent_id' => 5],
            ['name' => 'Пылесосы', 'slug' => 'vacuum-cleaners', 'parent_id' => 5],
            
            // Подкатегории для Аксессуаров
            ['name' => 'Чехлы и защита', 'slug' => 'cases', 'parent_id' => 6],
            ['name' => 'Зарядные устройства', 'slug' => 'chargers', 'parent_id' => 6],
            ['name' => 'Внешние аккумуляторы', 'slug' => 'power-banks', 'parent_id' => 6],
            ['name' => 'Кабели и переходники', 'slug' => 'cables', 'parent_id' => 6],
            ['name' => 'Карты памяти', 'slug' => 'memory-cards', 'parent_id' => 6],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}