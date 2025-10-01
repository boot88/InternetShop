<?php
// database/seeders/ExtendedProductsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtendedProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // СМАРТФОНЫ (15 товаров)
            [
                'name' => 'iPhone 15 Pro 128GB',
                'slug' => 'iphone-15-pro-128gb',
                'sku' => 'APPLE-IP15P-128',
                'description' => 'iPhone 15 Pro с титановым корпусом, кнопкой действия и процессором A17 Pro.',
                'short_description' => 'Новый iPhone 15 Pro с титаном',
                'price' => 109999.00,
                'compare_price' => 119999.00,
                'cost' => 85000.00,
                'brand_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.187,
                'dimensions' => '146.6x70.6x8.3 mm'
            ],
            [
                'name' => 'Samsung Galaxy Z Flip5',
                'slug' => 'samsung-galaxy-z-flip5',
                'sku' => 'SAMSUNG-ZFLIP5-256',
                'description' => 'Складной смартфон с гибким экраном и улучшенным внешним дисплеем.',
                'short_description' => 'Складной смартфон от Samsung',
                'price' => 89999.00,
                'compare_price' => 99999.00,
                'cost' => 70000.00,
                'brand_id' => 2,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.187,
                'dimensions' => '165.1x71.9x6.9 mm'
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'slug' => 'google-pixel-8-pro',
                'sku' => 'GOOGLE-PIX8P-128',
                'description' => 'Флагманский смартфон Google с процессором Tensor G3 и продвинутой камерой.',
                'short_description' => 'Флагман от Google с ИИ',
                'price' => 84999.00,
                'compare_price' => 89999.00,
                'cost' => 65000.00,
                'brand_id' => 7,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.213,
                'dimensions' => '162.6x76.5x8.8 mm'
            ],
            [
                'name' => 'OnePlus 11 5G',
                'slug' => 'oneplus-11-5g',
                'sku' => 'ONEPLUS-11-256',
                'description' => 'Мощный смартфон с Snapdragon 8 Gen 2 и быстрой зарядкой 100W.',
                'short_description' => 'Флагманская производительность',
                'price' => 64999.00,
                'compare_price' => 69999.00,
                'cost' => 50000.00,
                'brand_id' => 8,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.205,
                'dimensions' => '163.1x74.1x8.5 mm'
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro',
                'slug' => 'xiaomi-redmi-note-13-pro',
                'sku' => 'XIAOMI-RN13P-128',
                'description' => 'Смартфон среднего класса с камерой 200 МП и AMOLED экраном.',
                'short_description' => 'Камера 200 МП по доступной цене',
                'price' => 29999.00,
                'compare_price' => 34999.00,
                'cost' => 22000.00,
                'brand_id' => 3,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.187,
                'dimensions' => '161.1x75.0x7.9 mm'
            ],
            [
                'name' => 'Realme GT Neo 5',
                'slug' => 'realme-gt-neo-5',
                'sku' => 'REALME-GTN5-256',
                'description' => 'Игровой смартфон с подсветкой и зарядкой 240W.',
                'short_description' => 'Игровой смартфон с RGB',
                'price' => 39999.00,
                'compare_price' => 44999.00,
                'cost' => 30000.00,
                'brand_id' => 21,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.199,
                'dimensions' => '163.9x75.8x8.9 mm'
            ],
            [
                'name' => 'Nothing Phone (2)',
                'slug' => 'nothing-phone-2',
                'sku' => 'NOTHING-PHONE2-256',
                'description' => 'Уникальный дизайн с Glyph Interface и прозрачной задней панелью.',
                'short_description' => 'Уникальный дизайн с подсветкой',
                'price' => 49999.00,
                'compare_price' => 54999.00,
                'cost' => 38000.00,
                'brand_id' => 22,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.201,
                'dimensions' => '162.1x76.4x8.6 mm'
            ],
            [
                'name' => 'ASUS ROG Phone 7',
                'slug' => 'asus-rog-phone-7',
                'sku' => 'ASUS-ROG7-512',
                'description' => 'Игровой смартфон с активным охлаждением и частотой обновления 165 Гц.',
                'short_description' => 'Профессиональный игровой смартфон',
                'price' => 79999.00,
                'compare_price' => 89999.00,
                'cost' => 60000.00,
                'brand_id' => 10,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.239,
                'dimensions' => '173.0x77.0x10.3 mm'
            ],

            // НОУТБУКИ (12 товаров)
            [
                'name' => 'MacBook Air 13" M2',
                'slug' => 'macbook-air-13-m2',
                'sku' => 'APPLE-MBA13-M2',
                'description' => 'Ультратонкий ноутбук с процессором Apple M2 и дисплеем Retina.',
                'short_description' => 'Легкий и мощный ультрабук',
                'price' => 119999.00,
                'compare_price' => 129999.00,
                'cost' => 90000.00,
                'brand_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.24,
                'dimensions' => '30.4x21.2x1.1 cm'
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'slug' => 'dell-xps-13-plus',
                'sku' => 'DELL-XPS13P-512',
                'description' => 'Премиальный ультрабук с безрамочным дисплеем и сенсорной панелью.',
                'short_description' => 'Флагманский ультрабук Dell',
                'price' => 149999.00,
                'compare_price' => 159999.00,
                'cost' => 110000.00,
                'brand_id' => 13,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.26,
                'dimensions' => '29.5x19.9x1.5 cm'
            ],
            [
                'name' => 'Lenovo Yoga 9i',
                'slug' => 'lenovo-yoga-9i',
                'sku' => 'LENOVO-YOGA9I-1TB',
                'description' => 'Трансформер премиум-класса с вращающимся динамоком и звуковой панелью.',
                'short_description' => 'Премиум трансформер 2-в-1',
                'price' => 129999.00,
                'compare_price' => 139999.00,
                'cost' => 95000.00,
                'brand_id' => 11,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.37,
                'dimensions' => '31.9x21.5x1.6 cm'
            ],
            [
                'name' => 'HP Spectre x360',
                'slug' => 'hp-spectre-x360',
                'sku' => 'HP-SPECTRE-X360-512',
                'description' => 'Стильный трансформер с OLED дисплеем и премиальным дизайном.',
                'short_description' => 'Элегантный трансформер HP',
                'price' => 134999.00,
                'compare_price' => 144999.00,
                'cost' => 100000.00,
                'brand_id' => 14,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.34,
                'dimensions' => '30.7x19.5x1.7 cm'
            ],
            [
                'name' => 'ASUS Zenbook 14X',
                'slug' => 'asus-zenbook-14x',
                'sku' => 'ASUS-ZEN14X-1TB',
                'description' => 'Ультрабук с OLED дисплеем 2.8K и процессором Intel Core i7.',
                'short_description' => 'Ультрабук с OLED дисплеем',
                'price' => 109999.00,
                'compare_price' => 119999.00,
                'cost' => 80000.00,
                'brand_id' => 10,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.4,
                'dimensions' => '31.2x22.1x1.7 cm'
            ],

            // ТЕЛЕВИЗОРЫ (8 товаров)
            [
                'name' => 'Sony Bravia XR A95L 65"',
                'slug' => 'sony-bravia-xr-a95l-65',
                'sku' => 'SONY-A95L-65',
                'description' => 'QLED телевизор с технологией Cognitive Processor XR и Acoustic Surface Audio+.',
                'short_description' => 'Флагманский QLED от Sony',
                'price' => 249999.00,
                'compare_price' => 279999.00,
                'cost' => 190000.00,
                'brand_id' => 4,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 28.5,
                'dimensions' => '144.6x83.1x26.5 cm'
            ],
            [
                'name' => 'LG G3 77" OLED',
                'slug' => 'lg-g3-77-oled',
                'sku' => 'LG-G3-77',
                'description' => 'OLED телевизор с технологией MLA для максимальной яркости и процессором α9 Gen6.',
                'short_description' => 'Яркий OLED с технологией MLA',
                'price' => 299999.00,
                'compare_price' => 329999.00,
                'cost' => 230000.00,
                'brand_id' => 5,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 32.8,
                'dimensions' => '172.1x99.2x28.9 cm'
            ],
            [
                'name' => 'Samsung The Frame 55"',
                'slug' => 'samsung-the-frame-55',
                'sku' => 'SAMSUNG-FRAME-55',
                'description' => 'Телевизор-картина с меняющимися рамками и режимом Art Mode.',
                'short_description' => 'Телевизор, который становится картиной',
                'price' => 89999.00,
                'compare_price' => 99999.00,
                'cost' => 65000.00,
                'brand_id' => 2,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 19.2,
                'dimensions' => '123.2x71.7x24.9 cm'
            ],
            [
                'name' => 'TCL C745 65" QLED',
                'slug' => 'tcl-c745-65-qled',
                'sku' => 'TCL-C745-65',
                'description' => 'QLED телевизор с технологией Mini-LED и частотой обновления 144 Гц.',
                'short_description' => 'Игровой QLED с Mini-LED',
                'price' => 69999.00,
                'compare_price' => 79999.00,
                'cost' => 50000.00,
                'brand_id' => 23,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 23.1,
                'dimensions' => '144.8x83.2x27.3 cm'
            ],

            // НАУШНИКИ И АУДИО (10 товаров)
            [
                'name' => 'Sony WH-1000XM4',
                'slug' => 'sony-wh-1000xm4',
                'sku' => 'SONY-WH1000XM4',
                'description' => 'Беспроводные наушники с улучшенным шумоподавлением и автономностью 30 часов.',
                'short_description' => 'Легендарные наушники с шумоподавлением',
                'price' => 24999.00,
                'compare_price' => 29999.00,
                'cost' => 18000.00,
                'brand_id' => 4,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.254,
                'dimensions' => '18.5x16.8x7.3 cm'
            ],
            [
                'name' => 'Bose QuietComfort Ultra',
                'slug' => 'bose-quietcomfort-ultra',
                'sku' => 'BOSE-QC-ULTRA',
                'description' => 'Премиальные наушники с технологией Immersive Audio и адаптивным шумоподавлением.',
                'short_description' => 'Иммерсивный звук от Bose',
                'price' => 34999.00,
                'compare_price' => 39999.00,
                'cost' => 25000.00,
                'brand_id' => 17,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.248,
                'dimensions' => '18.2x16.5x7.1 cm'
            ],
            [
                'name' => 'JBL Flip 6',
                'slug' => 'jbl-flip-6',
                'sku' => 'JBL-FLIP6-BLUE',
                'description' => 'Портативная Bluetooth-колонка с защитой от воды IP67 и мощным звуком.',
                'short_description' => 'Портативная колонка с водозащитой',
                'price' => 8999.00,
                'compare_price' => 10999.00,
                'cost' => 6000.00,
                'brand_id' => 18,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.55,
                'dimensions' => '17.8x7.2x7.2 cm'
            ],
            [
                'name' => 'Marshall Stanmore III',
                'slug' => 'marshall-stanmore-iii',
                'sku' => 'MARSHALL-STAN3-BLK',
                'description' => 'Стационарная Bluetooth-колонка с фирменным дизайном Marshall и мощным звуком.',
                'short_description' => 'Культовая колонка от Marshall',
                'price' => 29999.00,
                'compare_price' => 34999.00,
                'cost' => 21000.00,
                'brand_id' => 24,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 4.25,
                'dimensions' => '35.0x19.5x19.0 cm'
            ],
            [
                'name' => 'Sennheiser Momentum 4',
                'slug' => 'sennheiser-momentum-4',
                'sku' => 'SENN-MOM4-BLACK',
                'description' => 'Беспроводные наушники с автономностью 60 часов и премиальным звуком.',
                'short_description' => 'Немецкое качество звука',
                'price' => 27999.00,
                'compare_price' => 32999.00,
                'cost' => 20000.00,
                'brand_id' => 25,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.293,
                'dimensions' => '18.0x16.5x7.8 cm'
            ],

            // ФОТОТЕХНИКА (8 товаров)
            [
                'name' => 'Nikon Z9',
                'slug' => 'nikon-z9',
                'sku' => 'NIKON-Z9-BODY',
                'description' => 'Флагманская беззеркальная камера с матрицей 45.7 МП и скоростью съемки 120 кадров/с.',
                'short_description' => 'Профессиональная беззеркалка Nikon',
                'price' => 449999.00,
                'compare_price' => 499999.00,
                'cost' => 350000.00,
                'brand_id' => 16,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 1.34,
                'dimensions' => '14.9x11.3x8.3 cm'
            ],
            [
                'name' => 'Canon RF 24-70mm f/2.8L',
                'slug' => 'canon-rf-24-70-f2-8',
                'sku' => 'CANON-RF24-70-28',
                'description' => 'Универсальный зум-объектив для полнокадровых беззеркальных камер Canon.',
                'short_description' => 'Профессиональный зум-объектив',
                'price' => 189999.00,
                'compare_price' => 209999.00,
                'cost' => 140000.00,
                'brand_id' => 15,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'weight' => 0.9,
                'dimensions' => '8.9x12.6 cm'
            ],
            [
                'name' => 'GoPro HERO12 Black',
                'slug' => 'gopro-hero12-black',
                'sku' => 'GOPRO-H12-BLACK',
                'description' => 'Экшн-камера с гиперстабилизацией HyperSmooth 6.0 и водонепроницаемостью 10м.',
                'short_description' => 'Лучшая экшн-камера',
                'price' => 34999.00,
                'compare_price' => 39999.00,
                'cost' => 25000.00,
                'brand_id' => 26,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.154,
                'dimensions' => '7.1x5.5x3.3 cm'
            ],
            [
                'name' => 'DJI Mini 4 Pro',
                'slug' => 'dji-mini-4-pro',
                'sku' => 'DJI-MINI4P-FLYMORE',
                'description' => 'Компактный дрон с камерой 4K/60fps, трек-системой и защитой от столкновений.',
                'short_description' => 'Компактный дрон с камерой 4K',
                'price' => 89999.00,
                'compare_price' => 99999.00,
                'cost' => 65000.00,
                'brand_id' => 27,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'weight' => 0.249,
                'dimensions' => '14.8x8.4x6.4 cm'
            ]
        ];

        foreach ($products as $product) {
            // Проверяем, существует ли уже товар с таким slug
            $existingProduct = DB::table('products')->where('slug', $product['slug'])->first();
            
            if (!$existingProduct) {
                $productId = DB::table('products')->insertGetId(array_merge($product, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));

                // Автоматическое связывание с категориями
                $this->assignCategories($productId, $product['brand_id'], $product['name']);
            }
        }
    }

    private function assignCategories($productId, $brandId, $productName)
    {
        $categories = [];

        // Определение категорий по названию товара
        if (str_contains(strtolower($productName), 'iphone') || 
            str_contains(strtolower($productName), 'samsung') || 
            str_contains(strtolower($productName), 'google') ||
            str_contains(strtolower($productName), 'oneplus') ||
            str_contains(strtolower($productName), 'xiaomi') ||
            str_contains(strtolower($productName), 'realme') ||
            str_contains(strtolower($productName), 'nothing') ||
            str_contains(strtolower($productName), 'asus') && 
            str_contains(strtolower($productName), 'phone')) {
            
            $categories[] = 1; // Смартфоны
            if (str_contains(strtolower($productName), 'iphone')) $categories[] = 7;
            if (str_contains(strtolower($productName), 'samsung')) $categories[] = 8;
            if (str_contains(strtolower($productName), 'xiaomi') || str_contains(strtolower($productName), 'realme')) $categories[] = 9;
            if (str_contains(strtolower($productName), 'asus') && str_contains(strtolower($productName), 'rog')) $categories[] = 10;
        }

        // Ноутбуки
        if (str_contains(strtolower($productName), 'macbook') || 
            str_contains(strtolower($productName), 'xps') || 
            str_contains(strtolower($productName), 'yoga') ||
            str_contains(strtolower($productName), 'spectre') ||
            str_contains(strtolower($productName), 'zenbook')) {
            
            $categories[] = 2; // Ноутбуки
            if (str_contains(strtolower($productName), 'macbook')) $categories[] = 13;
            if (str_contains(strtolower($productName), 'asus') && str_contains(strtolower($productName), 'rog')) $categories[] = 11;
        }

        // Телевизоры
        if (str_contains(strtolower($productName), 'tv') || 
            str_contains(strtolower($productName), 'телевизор') || 
            str_contains(strtolower($productName), 'bravia') ||
            str_contains(strtolower($productName), 'oled') ||
            str_contains(strtolower($productName), 'qled')) {
            
            $categories[] = 3; // Телевизоры
            $categories[] = 16; // 4K телевизоры
            if (str_contains(strtolower($productName), 'oled')) $categories[] = 18;
        }

        // Наушники и аудио
        if (str_contains(strtolower($productName), 'headphone') || 
            str_contains(strtolower($productName), 'наушник') || 
            str_contains(strtolower($productName), 'wh-') ||
            str_contains(strtolower($productName), 'quietcomfort') ||
            str_contains(strtolower($productName), 'momentum') ||
            str_contains(strtolower($productName), 'flip') ||
            str_contains(strtolower($productName), 'stanmore') ||
            str_contains(strtolower($productName), 'колонка')) {
            
            $categories[] = 3; // Телевизоры и аудио
            $categories[] = 20; // Наушники
        }

        // Фототехника
        if (str_contains(strtolower($productName), 'canon') || 
            str_contains(strtolower($productName), 'nikon') || 
            str_contains(strtolower($productName), 'gopro') ||
            str_contains(strtolower($productName), 'dji') ||
            str_contains(strtolower($productName), 'объектив') ||
            str_contains(strtolower($productName), 'камера')) {
            
            $categories[] = 4; // Фототехника
            if (str_contains(strtolower($productName), 'canon') || str_contains(strtolower($productName), 'nikon')) {
                $categories[] = 22; // Беззеркальные камеры
            }
            if (str_contains(strtolower($productName), 'объектив')) $categories[] = 23;
            if (str_contains(strtolower($productName), 'gopro') || str_contains(strtolower($productName), 'dji')) $categories[] = 24;
        }

        // Удаляем дубликаты
        $categories = array_unique($categories);

        foreach ($categories as $categoryId) {
            DB::table('category_product')->insert([
                'category_id' => $categoryId,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}