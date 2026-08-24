<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

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
