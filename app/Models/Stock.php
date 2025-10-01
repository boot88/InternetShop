<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id', // исправляем на variant_id
        'quantity',
        'low_stock_threshold',
        'location'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        // Указываем правильное имя колонки
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->quantity === 0) return 'out_of_stock';
        if ($this->is_low_stock) return 'low_stock';
        return 'in_stock';
    }
}