<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        
        return true;
    }

    public function calculateDiscount($subtotal): float
    {
        if (!$this->isValid() || $subtotal < $this->min_order_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage' 
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->max_discount && $discount > $this->max_discount) {
            return $this->max_discount;
        }

        return min($discount, $subtotal);
    }
}