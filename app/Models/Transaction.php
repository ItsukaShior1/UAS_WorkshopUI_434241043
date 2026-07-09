<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    public const KIND_SUBSCRIPTION_PAYMENT = 'subscription_payment';
    public const KIND_BOOKKEEPING = 'bookkeeping';

    protected $fillable = [
        'user_id',
        'kind',
        'type',
        'item_name',
        'category',
        'amount',
        'quantity',
        'unit_price',
        'notes',
        'product_id',
        'item_image_data',
        'item_image_mime',
        'status',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'refund_reference',
        'refund_amount',
    ];

    protected $casts = [
        'amount' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'product_id' => 'integer',
        'cancelled_at' => 'datetime',
        'refund_amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeSubscriptionPayments($q)
    {
        return $q->where('kind', self::KIND_SUBSCRIPTION_PAYMENT);
    }

    public function scopeBookkeeping($q)
    {
        return $q->where('kind', self::KIND_BOOKKEEPING);
    }

    public function isCancellable(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isSubscriptionPayment(): bool
    {
        return $this->kind === self::KIND_SUBSCRIPTION_PAYMENT;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REFUNDED => 'Direfund',
            default => 'Berhasil',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CANCELLED => 'badge-rose',
            self::STATUS_REFUNDED => 'badge-amber',
            default => 'badge-emerald',
        };
    }
}