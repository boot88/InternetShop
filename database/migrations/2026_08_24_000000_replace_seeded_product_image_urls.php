<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const LOCAL_IMAGES = [
        1 => 'images/iphone15Pro.jfif', 2 => 'images/samsunggals23.avif',
        3 => 'images/photo-1598327105666-5b89351aff97.avif', 4 => 'images/macbookpro16.avif',
        5 => 'images/8-d.jpg', 6 => 'images/666c6648431eaj.jpg',
        7 => 'images/photo-1541807084-5c52b6b3adef.jfif', 8 => 'images/nikonz9.jfif',
        9 => 'images/w_120.webp', 10 => 'images/lgg377.jfif',
        11 => 'images/iphone15Pro.jfif', 12 => 'images/photo-1610945265064-0e34e5519bbf.jfif',
        13 => 'images/12_7azp_2x_jpg.png', 14 => 'images/f9bcaaf194d4299281da5.jpg',
        15 => 'images/photo-1598327105666-5b89351aff97.avif', 16 => 'images/samsunggals23.avif',
        17 => 'images/photo-1695048133142-1a20484d2569.jfif', 18 => 'images/samsunggals23.avif',
        19 => 'images/macbookpro16.avif', 20 => 'images/DellXPS13Plus932011.webp',
        21 => 'images/photo-1587614382346-4ec70e388b28.jfif', 22 => 'images/caa8846cba8d098985abb31f677ff226-hi.jpg',
        23 => 'images/asuszenbook.jfif', 24 => 'images/tcl.jpg', 25 => 'images/lgg377.jfif',
        26 => 'images/666c6648431ea.webp', 27 => 'images/photo-1541807084-5c52b6b3adef.jfif',
        28 => 'images/boesquetultra.avif', 29 => 'images/jblflip6.jfif', 30 => 'images/marshall.jfif',
        31 => 'images/nikonz9.jfif', 32 => 'images/nikonz9.jfif', 33 => 'images/photo-1700125621736-75a6d245a308.avif',
        34 => 'images/djimini.webp', 35 => 'images/tcl.jpg',
    ];

    public function up(): void
    {
        foreach (self::LOCAL_IMAGES as $productId => $path) {
            DB::table('product_images')
                ->where('product_id', $productId)
                ->where(function ($query) {
                    $query->where('image_path', 'like', 'https://images.unsplash.com/%')
                        ->orWhere('image_path', 'like', 'products/%');
                })
                ->update([
                    'image_path' => $path,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // The original external URLs are intentionally not restored.
    }
};
