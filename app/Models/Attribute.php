<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_filterable'
    ];

    protected $casts = [
        'is_filterable' => 'boolean'
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function productVariants()
    {
        return $this->hasManyThrough(
            ProductVariant::class,
            AttributeValue::class,
            'attribute_id',
            'id',
            'id',
            'id'
        );
    }
}