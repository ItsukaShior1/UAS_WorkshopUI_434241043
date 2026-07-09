<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_ABANDONED = 'abandoned';

    protected $fillable = ['user_id', 'status'];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function selectedItems()
    {
        return $this->items()->where('selected', true);
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(fn ($i) => $i->price * $i->quantity);
    }

    public function getSelectedSubtotalAttribute(): float
    {
        return (float) $this->selectedItems->sum(fn ($i) => $i->price * $i->quantity);
    }

    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    public function getSelectedQuantityAttribute(): int
    {
        return (int) $this->selectedItems->sum('quantity');
    }

    public function getHasSelectedAttribute(): bool
    {
        return $this->selectedItems()->exists();
    }

    public static function openFor($userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'status' => self::STATUS_OPEN],
        );
    }
}
