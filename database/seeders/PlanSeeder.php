<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => Plan::CODE_APP,
                'name' => 'Paket Bookify App',
                'description' => 'Akses penuh aplikasi Bookify untuk kelola keuangan, transaksi, dan komunitas UMKM.',
                'billing_period' => 'monthly',
                'price' => 49000,
                'includes_marketplace' => false,
                'features' => [
                    'Pencatatan transaksi otomatis',
                    'Dashboard analisis keuangan',
                    'Komunitas & artikel',
                    'Penyimpanan hingga 1.000 transaksi / bulan',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => Plan::CODE_APP,
                'name' => 'Paket Bookify App',
                'description' => 'Akses penuh aplikasi Bookify, lebih hemat dengan pembayaran tahunan.',
                'billing_period' => 'yearly',
                'price' => 490000,
                'includes_marketplace' => false,
                'features' => [
                    'Semua fitur App Bulanan',
                    'Hemat Rp 98.000 / tahun',
                    'Prioritas dukungan pelanggan',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => Plan::CODE_MARKETPLACE,
                'name' => 'Paket Bookify + Marketplace',
                'description' => 'Akses penuh Bookify + integrasi marketplace Shopee, Tokopedia, dan Lazada.',
                'billing_period' => 'monthly',
                'price' => 99000,
                'includes_marketplace' => true,
                'upgrade_discount_percent' => Plan::DEFAULT_UPGRADE_DISCOUNT_PERCENT,
                'features' => [
                    'Semua fitur App Bulanan',
                    'Integrasi Shopee',
                    'Integrasi Tokopedia',
                    'Integrasi Lazada',
                    'Sinkronisasi stok & pesanan',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => Plan::CODE_MARKETPLACE,
                'name' => 'Paket Bookify + Marketplace',
                'description' => 'Akses penuh + integrasi marketplace, lebih hemat dengan pembayaran tahunan.',
                'billing_period' => 'yearly',
                'price' => 990000,
                'includes_marketplace' => true,
                'upgrade_discount_percent' => Plan::DEFAULT_UPGRADE_DISCOUNT_PERCENT,
                'features' => [
                    'Semua fitur Marketplace Bulanan',
                    'Hemat Rp 198.000 / tahun',
                    'API key terenkripsi',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $p) {
            Plan::updateOrCreate(
                ['code' => $p['code'], 'billing_period' => $p['billing_period']],
                $p,
            );
        }
    }
}
