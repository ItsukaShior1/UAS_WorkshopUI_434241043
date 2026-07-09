<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public const CODE_APP = 'app';
    public const CODE_MARKETPLACE = 'marketplace';

    /**
     * Default diskon yang diberikan untuk user yang sudah berlangganan paket
     * Bookify App-only ketika ingin upgrade ke paket Marketplace (App + Marketplace).
     */
    public const DEFAULT_UPGRADE_DISCOUNT_PERCENT = 30;

    protected $fillable = [
        'code',
        'name',
        'description',
        'billing_period',
        'price',
        'includes_marketplace',
        'upgrade_discount_percent',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'includes_marketplace' => 'boolean',
        'upgrade_discount_percent' => 'integer',
        'is_active' => 'boolean',
        'features' => 'array',
        'sort_order' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeMarketplace($q)
    {
        return $q->where('code', self::CODE_MARKETPLACE);
    }

    public function scopeAppOnly($q)
    {
        return $q->where('code', self::CODE_APP);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function getBillingLabelAttribute(): string
    {
        return $this->billing_period === 'yearly' ? 'per tahun' : 'per bulan';
    }

    /**
     * Apakah paket ini termasuk kategori Marketplace (App + Marketplace).
     */
    public function isMarketplacePlan(): bool
    {
        return $this->code === self::CODE_MARKETPLACE;
    }

    /**
     * Hitung harga upgrade yang sudah didiskon untuk user tertentu.
     * Hanya berlaku jika user aktif di paket App-only dan plan ini adalah paket Marketplace.
     */
    public function upgradePriceFor(?User $user): ?array
    {
        if (! $this->isMarketplacePlan() || ! $user) {
            return null;
        }

        $active = $user->activeSubscription();
        if (! $active || ! $active->plan) {
            return null;
        }

        // Hanya berlaku untuk user yang saat ini ada di paket App-only
        if ($active->plan->code === self::CODE_MARKETPLACE) {
            return null;
        }

        $discountPercent = max(0, (int) ($this->upgrade_discount_percent ?: 0));
        if ($discountPercent === 0) {
            $discountPercent = self::DEFAULT_UPGRADE_DISCOUNT_PERCENT;
        }

        $original = (float) $this->price;
        $discountAmount = round($original * ($discountPercent / 100), 2);
        $final = max(0, $original - $discountAmount);

        return [
            'original_price' => $original,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'final_price' => $final,
            'is_upgrade' => true,
        ];
    }

    public function getEffectivePriceFor(?User $user): float
    {
        $upgrade = $this->upgradePriceFor($user);
        return $upgrade ? (float) $upgrade['final_price'] : (float) $this->price;
    }

    public function getFormattedEffectivePriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }
}