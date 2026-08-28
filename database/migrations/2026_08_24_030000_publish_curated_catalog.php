<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A small, deployable starter catalogue.  It is deliberately idempotent:
     * running this migration on a restored database updates these SKUs instead
     * of creating a second copy of each product.
     */
    public function up(): void
    {
        $now = now();
        $categories = [
            'smartphones' => ['Смартфоны', 'Актуальные смартфоны и аксессуары'],
            'laptops' => ['Ноутбуки', 'Ноутбуки для работы, учёбы и игр'],
            'photo' => ['Фото и видео', 'Камеры, объективы и дроны'],
            'audio' => ['Аудио', 'Наушники и портативная акустика'],
            'televisions' => ['Телевизоры', 'Телевизоры и домашние экраны'],
        ];
        foreach ($categories as $slug => [$name, $description]) {
            DB::table('categories')->updateOrInsert(['slug' => $slug], [
                'name' => $name, 'description' => $description, 'is_active' => true, 'updated_at' => $now,
                'created_at' => $now,
            ]);
        }

        $brands = ['Apple', 'Samsung', 'Google', 'Xiaomi', 'ASUS', 'Lenovo', 'Dell', 'Microsoft', 'Canon', 'Nikon', 'DJI', 'Bose', 'Sony', 'Marshall', 'JBL', 'LG', 'TCL'];
        foreach ($brands as $brand) {
            $slug = str($brand)->lower()->replace(' ', '-')->toString();
            DB::table('brands')->updateOrInsert(['slug' => $slug], [
                'name' => $brand, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now,
            ]);
        }

        $products = [
            ['Apple', 'smartphones', 'iPhone 16 Pro 128 ГБ', 'iPhone 16 Pro', 'APL-IP16P-128', 119990, 6, 'smartphone-0.webp', 'Компактный флагманский смартфон с OLED‑дисплеем и системой камер Pro.', 'OLED 6,3″ · 128 ГБ · 5G', 'Китай', 12],
            ['Samsung', 'smartphones', 'Samsung Galaxy S25 Ultra 256 ГБ', 'Galaxy S25 Ultra', 'SAM-S25U-256', 124990, 4, 'smartphone-1.webp', 'Флагманский Android‑смартфон с большим дисплеем, стилусом и универсальной камерой.', 'Dynamic AMOLED 2X 6,9″ · 256 ГБ · S Pen', 'Вьетнам', 12],
            ['Samsung', 'smartphones', 'Samsung Galaxy S25 128 ГБ', 'Galaxy S25', 'SAM-S25-128', 69990, 8, 'smartphone-2.webp', 'Сбалансированный смартфон для повседневной работы, фото и связи.', 'AMOLED 6,2″ · 128 ГБ · 5G', 'Вьетнам', 12],
            ['Google', 'smartphones', 'Google Pixel 9 Pro 128 ГБ', 'Pixel 9 Pro', 'GOO-PX9P-128', 89990, 3, 'smartphone-3.webp', 'Смартфон с чистой системой Android и акцентом на мобильную фотографию.', 'OLED 6,3″ · 128 ГБ · 5G', 'Вьетнам', 12],
            ['Xiaomi', 'smartphones', 'Xiaomi 14T Pro 256 ГБ', '14T Pro', 'XIA-14TP-256', 65990, 7, 'f9bcaaf194d4299281da5.webp', 'Производительный смартфон с большим AMOLED‑экраном и быстрой зарядкой.', 'AMOLED 6,67″ · 256 ГБ · 5G', 'Китай', 12],
            ['Apple', 'smartphones', 'iPhone 15 128 ГБ', 'iPhone 15', 'APL-IP15-128', 74990, 5, 'iphone15Pro.webp', 'Смартфон Apple с OLED‑дисплеем, USB‑C и двойной камерой.', 'OLED 6,1″ · 128 ГБ · USB‑C', 'Китай', 12],
            ['Samsung', 'smartphones', 'Samsung Galaxy S23 128 ГБ', 'Galaxy S23', 'SAM-S23-128', 54990, 4, 'samsunggals23.webp', 'Компактный Android‑смартфон с AMOLED‑дисплеем и тройной камерой.', 'AMOLED 6,1″ · 128 ГБ · 5G', 'Вьетнам', 12],
            ['Xiaomi', 'smartphones', 'Xiaomi Redmi Note 14 Pro 256 ГБ', 'Redmi Note 14 Pro', 'XIA-RN14P-256', 32990, 9, 'photo-1695048133142-1a20484d2569.webp', 'Смартфон среднего класса с большим экраном и увеличенным объёмом памяти.', 'AMOLED 6,67″ · 256 ГБ · 5G', 'Китай', 12],

            ['Apple', 'laptops', 'MacBook Air 13″ M4 16/256 ГБ', 'MacBook Air M4 (2025)', 'APL-MBA13-M4-256', 129990, 4, 'laptop-0.webp', 'Лёгкий ноутбук для учёбы, офиса и творческих задач.', '13,6″ · Apple M4 · 16 ГБ · SSD 256 ГБ', 'Китай', 12],
            ['Apple', 'laptops', 'MacBook Pro 14″ M4 Pro 24/512 ГБ', 'MacBook Pro M4 Pro (2024)', 'APL-MBP14-M4P-512', 239990, 2, 'laptop-1.webp', 'Профессиональный ноутбук для разработки, дизайна и монтажа.', '14,2″ · Apple M4 Pro · 24 ГБ · SSD 512 ГБ', 'Китай', 12],
            ['ASUS', 'laptops', 'ASUS Zenbook S 14 OLED UX5406', 'Zenbook S 14 OLED UX5406', 'ASU-UX5406-32-1T', 159990, 3, 'laptop-2.webp', 'Тонкий ноутбук с OLED‑экраном для мобильной работы и творчества.', '14″ OLED · Core Ultra · 32 ГБ · SSD 1 ТБ', 'Китай', 12],
            ['Lenovo', 'laptops', 'Lenovo Legion 5 15', 'Legion 5 15', 'LEN-LEG5-15-RTX', 139990, 2, 'laptop-3.webp', 'Игровой ноутбук с дискретной графикой и экраном высокой частоты.', '15,6″ · Ryzen 7 · RTX · SSD 1 ТБ', 'Китай', 12],
            ['Dell', 'laptops', 'Dell XPS 13 Plus 9320', 'XPS 13 Plus 9320', 'DEL-XPS9320-I7', 154990, 2, 'DellXPS13Plus932011.webp', 'Премиальный компактный ноутбук в минималистичном корпусе.', '13,4″ · Core i7 · 16 ГБ · SSD 512 ГБ', 'Китай', 12],
            ['Microsoft', 'laptops', 'Microsoft Surface Laptop 7 13,8″', 'Surface Laptop 7', 'MS-SL7-13-512', 149990, 3, 'photo-1592899677977-9c10ca588bbd.webp', 'Тонкий ноутбук с сенсорным дисплеем для работы и поездок.', '13,8″ · 16 ГБ · SSD 512 ГБ', 'Китай', 12],

            ['Canon', 'photo', 'Canon EOS R6 Mark II Body', 'EOS R6 Mark II', 'CAN-R6M2-BODY', 224990, 2, 'photo-video-0.webp', 'Полнокадровая беззеркальная камера для фото и видео.', 'Full Frame · 24,2 Мп · 4K', 'Япония', 24],
            ['Nikon', 'photo', 'Nikon Z9 Body', 'Z9', 'NIK-Z9-BODY', 489990, 1, 'nikonz9.webp', 'Профессиональная беззеркальная камера для репортажа и видеопроизводства.', 'Full Frame · 45,7 Мп · 8K', 'Таиланд', 24],
            ['Canon', 'photo', 'Canon RF 24-70mm f/2.8L IS USM', 'RF 24-70mm F2.8L IS USM', 'CAN-RF2470-F28L', 249990, 3, 'photo-video-1.webp', 'Профессиональный светосильный зум‑объектив RF 24–70 мм.', 'RF · 24–70 мм · f/2.8 · стабилизация', 'Япония', 24],
            ['DJI', 'photo', 'DJI Mini 4 Pro', 'Mini 4 Pro', 'DJI-MINI4P-RC2', 109990, 4, 'djimini.webp', 'Компактный дрон с камерой и всенаправленным обнаружением препятствий.', 'до 249 г · 4K · до 45 мин', 'Китай', 12],
            ['DJI', 'photo', 'DJI Air 3S Fly More Combo', 'Air 3S', 'DJI-AIR3S-FMC', 179990, 2, 'photo-video-2.webp', 'Дрон с двумя камерами для путешествий и видеосъёмки.', 'две камеры · 4K · до 45 мин', 'Китай', 12],
            ['DJI', 'photo', 'DJI Osmo Pocket 3', 'Osmo Pocket 3', 'DJI-OSMO-P3', 69990, 4, 'photo-video-3.webp', 'Карманная камера на стабилизаторе для прогулок, влогов и коротких видео.', '1″ CMOS · 4K · трёхосевая стабилизация', 'Китай', 12],

            ['Bose', 'audio', 'Bose QuietComfort Ultra Headphones', 'QuietComfort Ultra', 'BOS-QCU-HP', 45990, 5, 'boesquetultra.webp', 'Полноразмерные наушники с активным шумоподавлением.', 'Bluetooth · ANC · до 24 ч', 'Китай', 12],
            ['Sony', 'audio', 'Sony WH-1000XM6', 'WH-1000XM6', 'SON-WH1000XM6', 49990, 4, 'w_120.webp', 'Беспроводные наушники с адаптивным шумоподавлением.', 'Bluetooth · ANC · до 30 ч', 'Малайзия', 12],
            ['Marshall', 'audio', 'Marshall Major V', 'Major V', 'MAR-MAJOR-V', 16990, 8, 'marshall.webp', 'Складные беспроводные наушники с длительным временем работы.', 'Bluetooth · до 100 ч', 'Китай', 12],
            ['JBL', 'audio', 'JBL Flip 6', 'Flip 6', 'JBL-FLIP6-BLK', 11990, 10, 'jblflip6.webp', 'Защищённая портативная Bluetooth‑колонка для дома и прогулок.', 'Bluetooth · IP67 · до 12 ч', 'Китай', 12],
            ['Bose', 'audio', 'Bose QuietComfort Headphones', 'QuietComfort', 'BOS-QC-HP', 34990, 4, 'orig.webp', 'Комфортные полноразмерные наушники с шумоподавлением.', 'Bluetooth · ANC · до 24 ч', 'Китай', 12],

            ['LG', 'televisions', 'LG OLED55C5RLA 55″', 'OLED55C5RLA', 'LG-OLED55C5', 159990, 3, 'lgg377.webp', 'OLED‑телевизор 4K для фильмов, игр и стриминга.', '55″ · OLED · 4K · Smart TV', 'Польша', 12],
            ['TCL', 'televisions', 'TCL 65C6K 65″', '65C6K', 'TCL-65C6K', 99990, 4, 'tcl.webp', 'Большой 4K‑телевизор с QD‑Mini LED подсветкой.', '65″ · QD‑Mini LED · 4K · Google TV', 'Китай', 12],
            ['Samsung', 'televisions', 'Samsung Neo QLED 65″', 'Neo QLED 65', 'SAM-NEOQLED-65', 149990, 3, '666c6648431ea.webp', '4K‑телевизор с яркой подсветкой и игровыми функциями.', '65″ · Neo QLED · 4K · Smart TV', 'Венгрия', 12],
            ['Samsung', 'televisions', 'Samsung QLED 55″', 'QLED 55', 'SAM-QLED-55', 89990, 5, '666c6648431eaj.webp', '4K‑телевизор для гостиной с поддержкой Smart TV.', '55″ · QLED · 4K · Smart TV', 'Венгрия', 12],
            ['Sony', 'televisions', 'Sony BRAVIA 55″ 4K', 'BRAVIA 55 4K', 'SON-BRAVIA-55', 129990, 2, '8-d.webp', '4K‑телевизор для фильмов, консолей и приложений.', '55″ · 4K · Smart TV', 'Малайзия', 12],
        ];

        foreach ($products as [$brandName, $categorySlug, $name, $model, $sku, $price, $quantity, $image, $description, $shortDescription, $country, $warranty]) {
            $brandId = DB::table('brands')->where('name', $brandName)->value('id');
            $categoryId = DB::table('categories')->where('slug', $categorySlug)->value('id');
            $slug = str($name)->slug()->toString();
            // Legacy catalogue rows can already own the final slug under an
            // older SKU. The public slug is the stable identity, so update
            // that row instead of attempting to insert a duplicate product.
            DB::table('products')->updateOrInsert(['slug' => $slug], [
                'sku' => $sku, 'name' => $name, 'model' => $model, 'brand_id' => $brandId,
                'description' => $description, 'short_description' => $shortDescription,
                'price' => $price, 'compare_price' => null, 'is_active' => true,
                'is_featured' => in_array($sku, ['DJI-MINI4P-RC2', 'APL-MBA13-M4-256', 'SAM-S25U-256'], true),
                'has_variants' => false, 'weight' => null, 'dimensions' => null,
                'country_of_origin' => $country, 'warranty_months' => $warranty,
                'package_contents' => 'Товар, документация и комплектующие производителя. Точный комплект проверяется при подтверждении заказа.',
                'price_verified_at' => null, 'meta_title' => $name.' — купить в TechZone',
                'meta_description' => $description, 'updated_at' => $now, 'created_at' => $now,
            ]);
            $productId = DB::table('products')->where('sku', $sku)->value('id');
            DB::table('category_product')->updateOrInsert(['category_id' => $categoryId, 'product_id' => $productId], ['updated_at' => $now, 'created_at' => $now]);
            DB::table('stocks')->updateOrInsert(['product_id' => $productId, 'variant_id' => null], [
                'quantity' => $quantity, 'low_stock_threshold' => 2, 'location' => 'Основной склад', 'updated_at' => $now, 'created_at' => $now,
            ]);
            DB::table('product_images')->updateOrInsert(['product_id' => $productId, 'variant_id' => null, 'order' => 0], [
                'image_path' => 'images/catalog/'.$image, 'is_main' => true,
                'alt_text' => 'Иллюстративное фото: '.$name, 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $skus = ['APL-IP16P-128', 'SAM-S25U-256', 'SAM-S25-128', 'GOO-PX9P-128', 'XIA-14TP-256', 'APL-IP15-128', 'SAM-S23-128', 'XIA-RN14P-256', 'APL-MBA13-M4-256', 'APL-MBP14-M4P-512', 'ASU-UX5406-32-1T', 'LEN-LEG5-15-RTX', 'DEL-XPS9320-I7', 'MS-SL7-13-512', 'CAN-R6M2-BODY', 'NIK-Z9-BODY', 'CAN-RF2470-F28L', 'DJI-MINI4P-RC2', 'DJI-AIR3S-FMC', 'DJI-OSMO-P3', 'BOS-QCU-HP', 'SON-WH1000XM6', 'MAR-MAJOR-V', 'JBL-FLIP6-BLK', 'BOS-QC-HP', 'LG-OLED55C5', 'TCL-65C6K', 'SAM-NEOQLED-65', 'SAM-QLED-55', 'SON-BRAVIA-55'];
        $ids = DB::table('products')->whereIn('sku', $skus)->pluck('id');
        DB::table('products')->whereIn('id', $ids)->delete();
    }
};
