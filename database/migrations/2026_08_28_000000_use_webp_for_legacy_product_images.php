<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'nikon-z9' => 'images/catalog/nikonz9.webp',
            'nikon-z9-body' => 'images/catalog/nikonz9.webp',
            'sony-wh-1000xm4' => 'images/catalog/w_120.webp',
            'sony-wh-1000xm6' => 'images/catalog/w_120.webp',
        ];

        foreach ($images as $slug => $path) {
            $product = DB::table('products')->where('slug', $slug)->first();

            if (! $product) {
                continue;
            }

            DB::table('product_images')
                ->where('product_id', $product->id)
                ->update(['is_main' => false, 'updated_at' => now()]);

            DB::table('product_images')->updateOrInsert(
                [
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'order' => 0,
                ],
                [
                    'image_path' => $path,
                    'is_main' => true,
                    'alt_text' => $product->name,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        // WebP paths are a compatibility correction and should not be reverted.
    }
};
