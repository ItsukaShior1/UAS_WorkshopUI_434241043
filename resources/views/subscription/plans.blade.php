@extends('layouts.dashboard')

@section('page-title', 'Pilih Paket')

@section('dashboard-content')
<div class="plans-shell">
    <div class="plans-header">
        <p class="plans-kicker">Langganan Bookify</p>
        <h2>Pilih paket yang sesuai untuk usaha Anda</h2>
        <p>Berhenti kapan saja. Pembayaran aman Midtrans-style (mock).</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    @if ($mySub)
        <div class="active-banner">
            <div class="active-banner-icon"><i data-lucide="crown"></i></div>
            <div class="active-banner-body">
                <strong>Anda sedang berlangganan {{ $mySub->plan->name }} ({{ $mySub->plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }})</strong>
                <p>Berlaku hingga {{ $mySub->ends_at->translatedFormat('d F Y') }} @if($mySub->plan->includes_marketplace) • Sudah termasuk akses Marketplace @else • Hanya akses Bookify App @endif</p>
            </div>
            <a href="{{ route('subscription.my') }}" class="btn-link">Kelola</a>
        </div>
    @endif

    @if ($isAppOnlyActive)
        <div class="upgrade-banner">
            <div class="upgrade-banner-content">
                <div class="upgrade-banner-tag"><i data-lucide="zap"></i> Penawaran Upgrade</div>
                <h3>Tambah akses Marketplace dengan diskon khusus</h3>
                <p>Pelanggan setia yang sudah menggunakan Bookify App berhak atas <strong>potongan harga otomatis</strong> saat upgrade ke paket Marketplace. Sistem akan mendeteksi langganan Anda dan menerapkan diskon secara real-time.</p>
                <ul class="upgrade-banner-list">
                    <li><i data-lucide="check-circle-2"></i> Diskon {{ \App\Models\Plan::DEFAULT_UPGRADE_DISCOUNT_PERCENT }}% otomatis dari paket Marketplace</li>
                    <li><i data-lucide="check-circle-2"></i> Hanya berlaku untuk upgrade App → Marketplace</li>
                    <li><i data-lucide="check-circle-2"></i> Tetap mempertahankan periode langganan Anda</li>
                </ul>
            </div>
            <div class="upgrade-banner-art" aria-hidden="true">
                <i data-lucide="rocket"></i>
            </div>
        </div>
    @endif

    <div class="plans-grid">
        @foreach ($plansContext as $ctx)
            @php
                $plan = $ctx['plan'];
                $upgrade = $ctx['upgrade'];
                $isCurrent = $ctx['is_current'];
                $isSameCode = $ctx['is_same_code'];
                $featured = $plan->includes_marketplace;
            @endphp
            <div class="plan-card @if($featured) plan-card-featured @endif @if($isCurrent) plan-card-current @endif">
                @if ($featured)
                    <span class="plan-badge">Rekomendasi</span>
                @elseif ($isCurrent)
                    <span class="plan-badge plan-badge-current">Paket Anda</span>
                @endif

                @if ($upgrade)
                    <span class="plan-discount-ribbon"><i data-lucide="zap"></i> Diskon Upgrade {{ $upgrade['discount_percent'] }}%</span>
                @endif

                <h3>{{ $plan->name }}</h3>
                <p class="plan-period">{{ $plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }}</p>

                <div class="plan-price">
                    @if ($upgrade)
                        <div class="plan-price-strike">{{ $plan->formatted_price }}</div>
                        <div class="plan-price-final">
                            <strong>Rp {{ number_format($upgrade['final_price'], 0, ',', '.') }}</strong>
                            <small> / {{ $plan->billing_period === 'yearly' ? 'tahun' : 'bulan' }}</small>
                        </div>
                        <div class="plan-price-saved">Hemat Rp {{ number_format($upgrade['discount_amount'], 0, ',', '.') }}</div>
                    @else
                        <strong>{{ $plan->formatted_price }}</strong><small> / {{ $plan->billing_period === 'yearly' ? 'tahun' : 'bulan' }}</small>
                    @endif
                </div>

                <p class="plan-desc">{{ $plan->description }}</p>

                <ul class="plan-features">
                    @foreach ((array) $plan->features as $feat)
                        <li><i data-lucide="check"></i> {{ $feat }}</li>
                    @endforeach
                    @if ($plan->includes_marketplace)
                        <li class="plan-feature-mp"><i data-lucide="store"></i> Termasuk akses integrasi Marketplace</li>
                    @else
                        <li class="plan-feature-app"><i data-lucide="app-window"></i> Bookify App (manajemen usaha)</li>
                    @endif
                </ul>

                <div class="plan-actions">
                    <form method="POST" action="{{ route('subscription.add-to-cart', $plan) }}" class="plan-cart-form">
                        @csrf
                        <button type="submit" class="plan-cta-secondary" aria-label="Masukkan ke keranjang">
                            <i data-lucide="shopping-cart"></i>
                            <span>Keranjang</span>
                        </button>
                    </form>
                    <a href="{{ route('subscription.checkout', $plan) }}" class="plan-cta @if($isCurrent) plan-cta-current @endif">
                        @if ($isCurrent) Perpanjang @elseif($upgrade) Upgrade Sekarang @else Beli Sekarang @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
.plans-shell { display: flex; flex-direction: column; gap: 20px; }
.plans-header h2 { margin: 6px 0 4px; color: #123122; font-size: 26px; }
.plans-kicker { margin: 0; color: #5d7d6f; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }
.plans-header p { margin: 0; color: #54685f; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-success { background: #dcfce7; color: #166534; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert ul { margin: 0; padding-left: 18px; }

.active-banner { display: flex; gap: 16px; align-items: center; background: linear-gradient(135deg, #eaf9f0, #d1f4dd); border: 1px solid #bbf7d0; padding: 16px 20px; border-radius: 14px; }
.active-banner-icon { width: 44px; height: 44px; border-radius: 12px; background: #0f5a34; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.active-banner-icon i { width: 22px; height: 22px; }
.active-banner-body { flex: 1; }
.active-banner-body strong { color: #123122; font-size: 15px; }
.active-banner-body p { margin: 4px 0 0; color: #54685f; font-size: 13px; }
.btn-link { color: #0f5a34; font-weight: 700; text-decoration: none; padding: 8px 14px; border-radius: 10px; background: #fff; border: 1px solid #bbf7d0; flex-shrink: 0; }
.btn-link:hover { background: #f1faf5; }

.upgrade-banner { display: grid; grid-template-columns: 1fr 120px; gap: 24px; align-items: center; background: linear-gradient(135deg, #fff7e6 0%, #fde7c1 100%); border: 1px solid #fcd34d; padding: 24px; border-radius: 18px; position: relative; overflow: hidden; }
.upgrade-banner-content { display: flex; flex-direction: column; gap: 8px; }
.upgrade-banner-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; background: #b45309; color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; align-self: flex-start; }
.upgrade-banner-tag i { width: 12px; height: 12px; }
.upgrade-banner-content h3 { margin: 4px 0; color: #7c2d12; font-size: 18px; }
.upgrade-banner-content p { margin: 0; color: #92400e; font-size: 14px; line-height: 1.5; }
.upgrade-banner-list { list-style: none; padding: 0; margin: 8px 0 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 6px; }
.upgrade-banner-list li { display: flex; align-items: center; gap: 8px; color: #7c2d12; font-size: 13px; font-weight: 600; }
.upgrade-banner-list i { width: 16px; height: 16px; color: #b45309; flex-shrink: 0; }
.upgrade-banner-art { display: flex; align-items: center; justify-content: center; color: rgba(180, 83, 9, .25); }
.upgrade-banner-art i { width: 80px; height: 80px; }
@media (max-width: 720px) { .upgrade-banner { grid-template-columns: 1fr; } .upgrade-banner-art { display: none; } }

.plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
.plan-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; position: relative; display: flex; flex-direction: column; gap: 10px; transition: transform .15s ease, box-shadow .15s ease; overflow: hidden; }
.plan-card:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(15, 90, 52, .12); }
.plan-card-featured { border-color: #0f5a34; box-shadow: 0 12px 28px rgba(15, 90, 52, .15); }
.plan-card-current { border-color: #f59e0b; box-shadow: 0 12px 28px rgba(245, 158, 11, .15); }

.plan-badge { position: absolute; top: 14px; right: 14px; background: #0f5a34; color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 700; }
.plan-badge-current { background: #f59e0b; }
.plan-discount-ribbon { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 11px; font-weight: 700; align-self: flex-start; box-shadow: 0 4px 10px rgba(217, 119, 6, .25); }
.plan-discount-ribbon i { width: 12px; height: 12px; }

.plan-card h3 { margin: 0; color: #123122; font-size: 18px; }
.plan-period { margin: 0; color: #5d7d6f; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; }
.plan-price { display: flex; flex-direction: column; gap: 2px; }
.plan-price strong { font-size: 30px; font-weight: 700; color: #123122; }
.plan-price small { font-size: 14px; color: #5d7d6f; font-weight: 500; }
.plan-price-strike { font-size: 14px; color: #94a3b8; text-decoration: line-through; }
.plan-price-final { display: flex; align-items: baseline; gap: 4px; flex-wrap: wrap; }
.plan-price-final strong { font-size: 32px; font-weight: 800; color: #d97706; }
.plan-price-saved { font-size: 12px; color: #d97706; font-weight: 700; background: #fef3c7; padding: 3px 10px; border-radius: 999px; align-self: flex-start; }
.plan-desc { color: #54685f; font-size: 14px; line-height: 1.5; margin: 0; }
.plan-features { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
.plan-features li { display: flex; align-items: center; gap: 8px; color: #334155; font-size: 14px; }
.plan-features i { width: 16px; height: 16px; color: #0f5a34; flex-shrink: 0; }
.plan-feature-mp i, .plan-feature-mp { color: #b45309 !important; }
.plan-feature-app i, .plan-feature-app { color: #0f5a34 !important; }
.plan-actions { margin-top: auto; display: flex; gap: 8px; }
.plan-cart-form { flex: 0 0 auto; }
.plan-cta-secondary { display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #f1f5f9; color: #0f5a34; border: 1px solid #cbd5e1; padding: 12px 14px; border-radius: 12px; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 14px; transition: background .15s ease, transform .12s ease; }
.plan-cta-secondary:hover { background: #e2e8f0; }
.plan-cta-secondary:active { transform: scale(.97); }
.plan-cta-secondary i { width: 16px; height: 16px; }
.plan-cta { flex: 1; background: #0f5a34; color: #fff; padding: 12px; border-radius: 12px; text-align: center; text-decoration: none; font-weight: 600; transition: background .15s ease; font-size: 14px; }
.plan-cta:hover { background: #0a4326; }
.plan-cta-current { background: #f59e0b; }
.plan-cta-current:hover { background: #d97706; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
    });
</script>
@endpush