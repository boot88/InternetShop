<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    public function run(): void
    {
        // Known demo quantities. updateOrCreate makes the seeder safe to run again.
        $stocks = [
            ['variant_id' => 1, 'quantity' => 15, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 2, 'quantity' => 8, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 3, 'quantity' => 12, 'low_stock_threshold' => 5, 'location' => 'Санкт-Петербург'],
            ['variant_id' => 4, 'quantity' => 20, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 5, 'quantity' => 6, 'low_stock_threshold' => 5, 'location' => 'Москва'],
            ['variant_id' => 6, 'quantity' => 10, 'low_stock_threshold' => 3, 'location' => 'Москва'],
            ['variant_id' => 7, 'quantity' => 4, 'low_stock_threshold' => 3, 'location' => 'Санкт-Петербург'],
            ['variant_id' => 8, 'quantity' => 25, 'low_stock_threshold' => 10, 'location' => 'Москва'],
            ['product_id' => 6, 'quantity' => 7, 'low_stock_threshold' => 3, 'location' => 'Москва'],
            ['product_id' => 7, 'quantity' => 9, 'low_stock_threshold' => 3, 'location' => 'Санкт-Петербург'],
            ['product_id' => 8, 'quantity' => 5, 'low_stock_threshold' => 2, 'location' => 'Москва'],
            ['product_id' => 9, 'quantity' => 18, 'low_stock_threshold' => 5, 'location' => 'Москва'],
        ];

        foreach ($stocks as $data) {
            $key = array_key_exists('variant_id', $data)
                ? ['variant_id' => $data['variant_id']]
                : ['product_id' => $data['product_id'], 'variant_id' => null];

            Stock::updateOrCreate($key, $data);
        }

        // Products imported with has_variants=1 but without real variant records
        // are purchasable as ordinary products until variants are entered.
        Product::active()->doesntHave('variants')->select('id')->each(function (Product $product) {
            Stock::firstOrCreate(
                ['product_id' => $product->id, 'variant_id' => null],
                ['quantity' => 10, 'low_stock_threshold' => 3, 'location' => 'Основной склад'],
            );
        });

        ProductVariant::where('is_active', true)->select('id')->each(function (ProductVariant $variant) {
            Stock::firstOrCreate(
                ['variant_id' => $variant->id],
                ['quantity' => 10, 'low_stock_threshold' => 3, 'location' => 'Основной склад'],
            );
        });
    }
}
