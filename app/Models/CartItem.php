<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'purchasable_type',
        'purchasable_id',
        'name',
        'description',
        'price',
        'original_price',
        'quantity',
        'image_mime',
        'image_data',
        'selected',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'quantity' => 'integer',
        'selected' => 'boolean',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function purchasable()
    {
        return $this->morphTo();
    }

    public function getLineTotalAttribute(): float
    {
        return (float) $this->price * (int) $this->quantity;
    }

    public function getImageDataUriAttribute(): ?string
    {
        if (!$this->image_data || !$this->image_mime) {
            return null;
        }
        return 'data:'.$this->image_mime.';base64,'.$this->image_data;
    }

    public function getSubtitleAttribute(): ?string
    {
        if ($this->purchasable_type === Plan::class) {
            $period = $this->purchasable?->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan';
            return 'Langganan '.$period;
        }
        return $this->description;
    }

    public function getIsSubscriptionAttribute(): bool
    {
        return $this->purchasable_type === Plan::class;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price !== null
            && (float) $this->original_price > (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->has_discount) {
            return 0;
        }
        $original = (float) $this->original_price;
        $final = (float) $this->price;
        if ($original <= 0) {
            return 0;
        }
        return (int) round((($original - $final) / $original) * 100);
    }
}
