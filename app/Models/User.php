<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'business_type', 'role', 'is_active', 'deactivated_reason'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(CommunityPost::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class)->latest('id');
    }

    public function activeSubscription(): ?UserSubscription
    {
        UserSubscription::syncExpired();
        return $this->subscriptions()
            ->where('status', UserSubscription::STATUS_ACTIVE)
            ->where('ends_at', '>', now())
            ->latest('id')
            ->first();
    }

    public function hasMarketplaceAccess(): bool
    {
        $sub = $this->activeSubscription();
        return $sub && $sub->plan && $sub->plan->includes_marketplace;
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function marketplaceIntegrations(): HasMany
    {
        return $this->hasMany(MarketplaceIntegration::class);
    }

    public function deactivateForExpiredSubscription(): void
    {
        if (!$this->is_active || $this->role === self::ROLE_ADMIN) {
            return;
        }
        // Whitelist akun pre-subscription era yang tidak boleh auto-deactivate
        if (in_array($this->email, ['admin@bookify.com', 'toko@bookify.com'], true)) {
            return;
        }
        $this->forceFill([
            'is_active' => false,
            'deactivated_reason' => 'Langganan berakhir',
        ])->save();
    }
}
