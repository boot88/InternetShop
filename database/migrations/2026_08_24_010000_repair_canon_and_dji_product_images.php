<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach ([
            'iphone-15-pro-max' => 'images/iphone15Pro.jfif',
            'samsung-galaxy-s23-ultra' => 'images/samsunggals23.avif',
            'xiaomi-13-pro' => 'images/photo-1598327105666-5b89351aff97.avif',
            'macbook-pro-16-m2-max' => 'images/macbookpro16.avif',
            'asus-rog-strix-g18' => 'images/8-d.jpg',
            'samsung-qled-4k-65' => 'images/666c6648431eaj.jpg',
            'lg-oled-55-c3' => 'images/lgg377.jfif',
            'canon-eos-r6-mark-ii' => 'images/nikonz9.jfif',
            'sony-wh-1000xm5' => 'images/w_120.webp',
            'apple-airpods-pro-2' => 'images/photo-1541807084-5c52b6b3adef.jfif',
            'iphone-15-pro-128gb' => 'images/iphone15Pro.jfif',
            'samsung-galaxy-z-flip5' => 'images/photo-1610945265064-0e34e5519bbf.jfif',
            'google-pixel-8-pro' => 'images/12_7azp_2x_jpg.png',
            'oneplus-11-5g' => 'images/f9bcaaf194d4299281da5.jpg',
            'xiaomi-redmi-note-13-pro' => 'images/photo-1598327105666-5b89351aff97.avif',
            'realme-gt-neo-5' => 'images/samsunggals23.avif',
            'nothing-phone-2' => 'images/photo-1695048133142-1a20484d2569.jfif',
            'asus-rog-phone-7' => 'images/samsunggals23.avif',
            'macbook-air-13-m2' => 'images/macbookpro16.avif',
            'dell-xps-13-plus' => 'images/DellXPS13Plus932011.webp',
            'lenovo-yoga-9i' => 'images/photo-1587614382346-4ec70e388b28.jfif',
            'hp-spectre-x360' => 'images/caa8846cba8d098985abb31f677ff226-hi.jpg',
            'asus-zenbook-14x' => 'images/asuszenbook.jfif',
            'sony-bravia-xr-a95l-65' => 'images/tcl.jpg',
            'lg-g3-77-oled' => 'images/lgg377.jfif',
            'samsung-the-frame-55' => 'images/666c6648431ea.webp',
            'tcl-c745-65-qled' => 'images/tcl.jpg',
            'sony-wh-1000xm4' => 'images/photo-1541807084-5c52b6b3adef.jfif',
            'bose-quietcomfort-ultra' => 'images/boesquetultra.avif',
            'jbl-flip-6' => 'images/jblflip6.jfif',
            'marshall-stanmore-iii' => 'images/marshall.jfif',
            'sennheiser-momentum-4' => 'images/w_120.webp',
            'nikon-z9' => 'images/nikonz9.jfif',
            'canon-rf-24-70-f2-8' => 'images/canon-rf-24-70-f2-8.webp',
            'gopro-hero12-black' => 'images/photo-1700125621736-75a6d245a308.avif',
            'dji-mini-4-pro' => 'images/djimini.webp',
        ] as $slug => $path) {
            $product = DB::table('products')->where('slug', $slug)->first();

            if ($product) {
                DB::table('product_images')
                    ->where('product_id', $product->id)
                    ->where('is_main', true)
                    ->update(['image_path' => $path, 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        // Image paths are content corrections and must not be reverted automatically.
    }
};
