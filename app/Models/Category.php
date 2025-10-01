<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'lft',
        'rgt',
        'depth'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    // Добавляем scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Добавляем scope root
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }

    public function getActiveChildrenAttribute()
    {
        return $this->children()->active()->get();
    }

    public function getAllProductsCountAttribute()
    {
        $count = $this->products_count;
        
        foreach ($this->children as $child) {
            $count += $child->all_products_count;
        }
        
        return $count;
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    public function getBreadcrumbsAttribute()
    {
        $breadcrumbs = collect([$this]);
        
        $parent = $this->parent;
        while ($parent) {
            $breadcrumbs->prepend($parent);
            $parent = $parent->parent;
        }
        
        return $breadcrumbs;
    }
}