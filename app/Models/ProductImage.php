<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_main',
        'order'
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'order' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the image URL
     */
    public function getUrl()
{
    $p = trim((string) $this->image_path);

    if ($p === '') {
        return asset('images/placeholder.jpg');
    }

    // Если в БД уже лежит полный URL (Unsplash/и т.п.) — отдаём его как есть
    if (preg_match('~^https?://~i', $p)) {
        return $p;
    }

    // Если вдруг уже "storage/..."
    $p = ltrim($p, '/');
    if (str_starts_with($p, 'storage/')) {
        return asset($p);
    }

    // Если "public/..." — убираем public/
    if (str_starts_with($p, 'public/')) {
        $p = substr($p, 7);
    }

    return asset('storage/' . $p);
}
}