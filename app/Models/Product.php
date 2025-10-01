<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost',
        'brand_id',
        'is_featured',
        'is_active',
        'has_variants',
        'weight',
        'dimensions',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'has_variants' => 'boolean'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function getMainImageAttribute()
    {
        return $this->images->where('is_main', true)->first() 
            ?? $this->images->first();
    }

    public function getFinalPriceAttribute()
    {
        if ($this->has_variants && $this->variants->isNotEmpty()) {
            return $this->variants->min('price');
        }
        
        return $this->compare_price && $this->compare_price > $this->price 
            ? $this->compare_price 
            : $this->price;
    }

    public function getHasDiscountAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->contains(function ($variant) {
                return $variant->has_discount;
            });
        }
        
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->approved()->avg('rating') ?: 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->approved()->count();
    }

    public function getInStockAttribute(): bool
    {
        if ($this->has_variants) {
            return $this->variants->contains(function ($variant) {
                return $variant->stock && $variant->stock->in_stock;
            });
        }
        
        return $this->stock && $this->stock->in_stock;
    }

    public function getStockQuantityAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->sum(function ($variant) {
                return $variant->stock ? $variant->stock->quantity : 0;
            });
        }
        
        return $this->stock ? $this->stock->quantity : 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeWithBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }
}