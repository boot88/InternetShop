<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Static product photos included in public/images.
     *
     * Older database dumps store Unsplash URLs (and, in some cases, paths from
     * the old storage disk).  Resolve those known seeded records locally so
     * product cards do not depend on an external image host.
     */
    private const SEEDED_LOCAL_IMAGES = [
        1 => 'iphone15Pro.jfif',
        2 => 'samsunggals23.avif',
        3 => 'photo-1598327105666-5b89351aff97.avif',
        4 => 'macbookpro16.avif',
        5 => '8-d.jpg',
        6 => '666c6648431eaj.jpg',
        7 => 'photo-1541807084-5c52b6b3adef.jfif',
        8 => 'nikonz9.jfif',
        9 => 'w_120.webp',
        10 => 'lgg377.jfif',
        11 => 'iphone15Pro.jfif',
        12 => 'photo-1610945265064-0e34e5519bbf.jfif',
        13 => '12_7azp_2x_jpg.png',
        14 => 'f9bcaaf194d4299281da5.jpg',
        15 => 'photo-1598327105666-5b89351aff97.avif',
        16 => 'samsunggals23.avif',
        17 => 'photo-1695048133142-1a20484d2569.jfif',
        18 => 'samsunggals23.avif',
        19 => 'macbookpro16.avif',
        20 => 'DellXPS13Plus932011.webp',
        21 => 'photo-1587614382346-4ec70e388b28.jfif',
        22 => 'caa8846cba8d098985abb31f677ff226-hi.jpg',
        23 => 'asuszenbook.jfif',
        24 => 'tcl.jpg',
        25 => 'lgg377.jfif',
        26 => '666c6648431ea.webp',
        27 => 'photo-1541807084-5c52b6b3adef.jfif',
        28 => 'boesquetultra.avif',
        29 => 'jblflip6.jfif',
        30 => 'marshall.jfif',
        31 => 'nikonz9.jfif',
        32 => 'nikonz9.jfif',
        33 => 'photo-1700125621736-75a6d245a308.avif',
        34 => 'djimini.avif',
        35 => 'tcl.jpg',
    ];

    protected $fillable = [
        'product_id',
        'variant_id',
        'image_path',
        'is_main',
        'alt_text',
        'order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'order' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrl(): string
    {
        $p = trim((string) $this->image_path);

        if ($p === '') {
            return asset('images/placeholder.jpg');
        }

        $localSeededImage = self::SEEDED_LOCAL_IMAGES[$this->product_id] ?? null;

        // Databases created before local photos were added keep their old
        // Unsplash URLs.  Prefer the bundled files for those rows right away;
        // this also makes the fix work before a database migration is run.
        if ($localSeededImage && is_file(public_path('images/' . $localSeededImage))) {
            if (preg_match('~^https?://~i', $p) || str_starts_with(ltrim($p, '/'), 'products/')) {
                return asset('images/' . $localSeededImage);
            }
        }

        if (preg_match('~^https?://~i', $p)) {
            return $p;
        }

        $p = ltrim($p, '/');
        if (str_starts_with($p, 'storage/')) {
            return asset($p);
        }

        if (str_starts_with($p, 'public/')) {
            $p = substr($p, 7);
        }

        if (is_file(public_path($p))) {
            return asset($p);
        }

        // Seeded and imported product images may contain only the filename,
        // while the actual static files live in public/images.
        if (! str_contains($p, '/') && is_file(public_path('images/' . $p))) {
            return asset('images/' . $p);
        }

        return asset('storage/' . $p);
    }
}
