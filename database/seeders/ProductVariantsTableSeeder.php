<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantsTableSeeder extends Seeder
{
    public function run()
    {
        $variants = [
            // iPhone 15 Pro Max
            [
                'product_id' => 1,
                'sku' => 'APPLE-IP15PM-256-BLACK',
                'price' => 129999.00,
                'compare_price' => 139999.00,
                'cost' => 100000.00,
                'weight' => 0.22,
                'is_active' => true
            ],
            [
                'product_id' => 1,
                'sku' => 'APPLE-IP15PM-512-BLACK',
                'price' => 149999.00,
                'compare_price' => 159999.00,
                'cost' => 120000.00,
                'weight' => 0.22,
                'is_active' => true
            ],
            [
                'product_id' => 1,
                'sku' => 'APPLE-IP15PM-256-GOLD',
                'price' => 129999.00,
                'compare_price' => 139999.00,
                'cost' => 100000.00,
                'weight' => 0.22,
                'is_active' => true
            ],

            // Samsung Galaxy S23 Ultra
            [
                'product_id' => 2,
                'sku' => 'SAMSUNG-S23U-256-BLACK',
                'price' => 99999.00,
                'compare_price' => 109999.00,
                'cost' => 80000.00,
                'weight' => 0.23,
                'is_active' => true
            ],
            [
                'product_id' => 2,
                'sku' => 'SAMSUNG-S23U-512-GREEN',
                'price' => 119999.00,
                'compare_price' => 129999.00,
                'cost' => 95000.00,
                'weight' => 0.23,
                'is_active' => true
            ],

            // MacBook Pro
            [
                'product_id' => 4,
                'sku' => 'APPLE-MBP16-1TB-SILVER',
                'price' => 249999.00,
                'compare_price' => 269999.00,
                'cost' => 200000.00,
                'weight' => 2.15,
                'is_active' => true
            ],
            [
                'product_id' => 4,
                'sku' => 'APPLE-MBP16-2TB-SPACE-GRAY',
                'price' => 289999.00,
                'compare_price' => 309999.00,
                'cost' => 230000.00,
                'weight' => 2.15,
                'is_active' => true
            ],

            // AirPods Pro 2
            [
                'product_id' => 10,
                'sku' => 'APPLE-AIRPODSP2-WHITE',
                'price' => 24999.00,
                'compare_price' => 27999.00,
                'cost' => 18000.00,
                'weight' => 0.05,
                'is_active' => true
            ]
        ];

        foreach ($variants as $variant) {
            $variantId = DB::table('product_variants')->updateOrInsert(
                ['sku' => $variant['sku']],
                array_merge($variant, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );

            // Получаем ID вариации после вставки/обновления
            $variantRecord = DB::table('product_variants')->where('sku', $variant['sku'])->first();
            $variantId = $variantRecord->id;

            // Добавление атрибутов для вариаций
            if (str_contains($variant['sku'], 'BLACK')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 1],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 1, // Черный
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } elseif (str_contains($variant['sku'], 'GOLD')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 4],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 4, // Золотой
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } elseif (str_contains($variant['sku'], 'GREEN')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 7],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 7, // Зеленый
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } elseif (str_contains($variant['sku'], 'SILVER')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 3],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 3, // Серебристый
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } elseif (str_contains($variant['sku'], 'SPACE-GRAY')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 1],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 1, // Черный (Space Gray)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } elseif (str_contains($variant['sku'], 'WHITE')) {
                DB::table('product_variant_attributes')->updateOrInsert(
                    ['variant_id' => $variantId, 'attribute_value_id' => 2],
                    [
                        'variant_id' => $variantId,
                        'attribute_value_id' => 2, // Белый
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}