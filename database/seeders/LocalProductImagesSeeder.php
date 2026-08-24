<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Keeps demo image paths tied to permanent product slugs, never database IDs.
 * Product IDs differ between installs and must not determine an image.
 */
class LocalProductImagesSeeder extends Seeder
{
    private const IMAGES = [
        'iphone-15-pro-max' => 'iphone15Pro.jfif',
        'samsung-galaxy-s23-ultra' => 'samsunggals23.avif',
        'xiaomi-13-pro' => 'photo-1598327105666-5b89351aff97.avif',
        'macbook-pro-16-m2-max' => 'macbookpro16.avif',
        'asus-rog-strix-g18' => '8-d.jpg',
        'samsung-qled-4k-65' => '666c6648431eaj.jpg',
        'lg-oled-55-c3' => 'lgg377.jfif',
        'canon-eos-r6-mark-ii' => 'nikonz9.jfif',
        'sony-wh-1000xm5' => 'w_120.webp',
        'apple-airpods-pro-2' => 'photo-1541807084-5c52b6b3adef.jfif',
        'iphone-15-pro-128gb' => 'iphone15Pro.jfif',
        'samsung-galaxy-z-flip5' => 'photo-1610945265064-0e34e5519bbf.jfif',
        'google-pixel-8-pro' => '12_7azp_2x_jpg.png',
        'oneplus-11-5g' => 'f9bcaaf194d4299281da5.jpg',
        'xiaomi-redmi-note-13-pro' => 'photo-1598327105666-5b89351aff97.avif',
        'realme-gt-neo-5' => 'samsunggals23.avif',
        'nothing-phone-2' => 'photo-1695048133142-1a20484d2569.jfif',
        'asus-rog-phone-7' => 'samsunggals23.avif',
        'macbook-air-13-m2' => 'macbookpro16.avif',
        'dell-xps-13-plus' => 'DellXPS13Plus932011.webp',
        'lenovo-yoga-9i' => 'photo-1587614382346-4ec70e388b28.jfif',
        'hp-spectre-x360' => 'caa8846cba8d098985abb31f677ff226-hi.jpg',
        'asus-zenbook-14x' => 'asuszenbook.jfif',
        'sony-bravia-xr-a95l-65' => 'tcl.jpg',
        'lg-g3-77-oled' => 'lgg377.jfif',
        'samsung-the-frame-55' => '666c6648431ea.webp',
        'tcl-c745-65-qled' => 'tcl.jpg',
        'sony-wh-1000xm4' => 'photo-1541807084-5c52b6b3adef.jfif',
        'bose-quietcomfort-ultra' => 'boesquetultra.avif',
        'jbl-flip-6' => 'jblflip6.jfif',
        'marshall-stanmore-iii' => 'marshall.jfif',
        'sennheiser-momentum-4' => 'w_120.webp',
        'nikon-z9' => 'nikonz9.jfif',
        'canon-rf-24-70-f2-8' => 'canon-rf-24-70-f2-8.webp',
        'gopro-hero12-black' => 'photo-1700125621736-75a6d245a308.avif',
        'dji-mini-4-pro' => 'djimini.webp',
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            foreach (self::IMAGES as $slug => $filename) {
                $product = DB::table('products')->where('slug', $slug)->first();

                if (! $product) {
                    continue;
                }

                DB::table('product_images')
                    ->where('product_id', $product->id)
                    ->where('is_main', true)
                    ->update([
                        'image_path' => 'images/' . $filename,
                        'alt_text' => $product->name,
                        'updated_at' => now(),
                    ]);
            }
        });
    }
}
