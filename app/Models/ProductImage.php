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

        return asset('storage/' . $p);
    }
}
