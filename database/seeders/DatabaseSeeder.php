<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
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
        //ProductVariantAttributesTableSeeder::class,
        StocksTableSeeder::class,
        ProductImagesTableSeeder::class,
		ExtendedProductImagesTableSeeder::class,
        // ReviewsTableSeeder должен быть после UsersTableSeeder
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