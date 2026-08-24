<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total',
        'coupon_id',
        'customer_note',
        'shipping_address',
        'billing_address',
        'shipping_method',
        'payment_method',
        'payment_status',
        'stock_restored_at',
        'transaction_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'stock_restored_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function addHistory($status, $note = null)
    {
        return $this->histories()->create([
            'status' => $status,
            'note' => $note
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Ожидает подтверждения',
            'processing' => 'В обработке',
            'shipped' => 'Передан в доставку',
            'delivered' => 'Доставлен',
            'cancelled' => 'Отменён',
            'refunded' => 'Возврат оформлен',
            default => $this->status,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'online_prepayment' => 'Онлайн-предоплата',
            'card_on_delivery' => 'Оплата при получении',
            'bank_transfer' => 'Оплата по счёту',
            default => $this->payment_method,
        };
    }

    public function getShippingMethodLabelAttribute(): string
    {
        return match ($this->shipping_method) {
            'e2e4_pickup' => 'Самовывоз из пункта e2e4',
            'cdek_pickup' => 'Самовывоз из пункта СДЭК',
            'russian_post_pickup' => 'Самовывоз из отделения Почты России',
            'russian_post_courier' => 'Курьерская доставка Почтой России',
            'agreed_before_payment' => 'Согласуется с менеджером',
            default => $this->shipping_method,
        };
    }
}
