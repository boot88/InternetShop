<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing']) || ! (bool) env('SEED_DEMO_DATA', false)) {
            $this->command?->warn('Demo seeders are disabled. Set SEED_DEMO_DATA=true only in an isolated local environment.');

            return;
        }

        $this->call([
            UsersTableSeeder::class,
            BrandsTableSeeder::class,
            ExtendedBrandsTableSeeder::class,
            CategoriesTableSeeder::class,
            AttributesTableSeeder::class,
            AttributeValuesTableSeeder::class,
            ProductsTableSeeder::class,
            ExtendedProductsTableSeeder::class,
            CategoryProductTableSeeder::class,
            ProductVariantsTableSeeder::class,
            StocksTableSeeder::class,
            ExtendedProductImagesTableSeeder::class,
            LocalProductImagesSeeder::class,
            ReviewsTableSeeder::class,
            CouponsTableSeeder::class,
            CartsTableSeeder::class,
            CartItemsTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            OrderHistoriesTableSeeder::class,
        ]);
    }
}
