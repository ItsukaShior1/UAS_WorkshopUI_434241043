<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MarketplaceIntegration extends Model
{
    public const PLATFORM_SHOPEE = 'shopee';
    public const PLATFORM_TOKOPEDIA = 'tokopedia';
    public const PLATFORM_LAZADA = 'lazada';

    public const PLATFORMS = [
        self::PLATFORM_SHOPEE => 'Shopee',
        self::PLATFORM_TOKOPEDIA => 'Tokopedia',
        self::PLATFORM_LAZADA => 'Lazada',
    ];

    protected $fillable = [
        'user_id',
        'platform',
        'shop_name',
        'api_key_encrypted',
        'is_active',
        'connected_at',
        'last_sync_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'connected_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = ['api_key_encrypted'];

    protected function apiKeyEncrypted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : null,
            set: fn ($value) => $value ? encrypt($value) : null,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPlatformLabelAttribute(): string
    {
        return self::PLATFORMS[$this->platform] ?? ucfirst($this->platform);
    }

    public function getMaskedKeyAttribute(): string
    {
        $key = $this->api_key_encrypted ?? '';
        if (strlen($key) <= 6) {
            return str_repeat('*', max(strlen($key), 4));
        }
        return substr($key, 0, 3).str_repeat('*', max(strlen($key) - 6, 4)).substr($key, -3);
    }
}
