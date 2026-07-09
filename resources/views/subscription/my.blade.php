@extends('layouts.dashboard')

@section('page-title', 'Langganan Saya')

@section('dashboard-content')
<div class="mysub-shell">
    <div class="mysub-header">
        <h2>Langganan Saya</h2>
        <div class="mysub-header-actions">
            <a href="{{ route('subscription.payments') }}" class="btn-link"><i data-lucide="receipt"></i> Riwayat Pembayaran</a>
            <a href="{{ route('subscription.index') }}" class="btn-link">Lihat semua paket</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($active)
        <div class="active-card">
            <div class="active-card-body">
                <p class="active-kicker">Aktif</p>
                <h3>{{ $active->plan->name }} <span>({{ $active->plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }})</span></h3>
                <p>Berlaku hingga <strong>{{ $active->ends_at->translatedFormat('d F Y') }}</strong></p>
                <p class="active-meta">Metode: {{ strtoupper($active->payment_method) }} • Ref: {{ $active->payment_reference }}</p>
                @if ($active->plan->includes_marketplace)
                    <span class="active-chip"><i data-lucide="store"></i> Sudah termasuk akses Marketplace</span>
                @else
                    <span class="active-chip active-chip-app"><i data-lucide="app-window"></i> Hanya Bookify App</span>
                @endif
            </div>
            <form method="POST" action="{{ route('subscription.cancel', $active) }}">@csrf
                <button class="btn-danger-outline" type="submit" data-confirm="Batalkan langganan aktif?">Batalkan</button>
            </form>
        </div>
    @endif

    @if ($upgradeSuggestion)
        @php
            $sug = $upgradeSuggestion['plan'];
            $upg = $upgradeSuggestion['upgrade'];
        @endphp
        <div class="upgrade-suggest">
            <div class="upgrade-suggest-icon"><i data-lucide="zap"></i></div>
            <div class="upgrade-suggest-body">
                <strong>Upgrade ke {{ $sug->name }} ({{ $sug->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }})</strong>
                <p>Tambahkan integrasi Marketplace dengan harga khusus pelanggan setia.</p>
                @if ($upg)
                    <p class="upgrade-suggest-deal">
                        <span class="strike">Rp {{ number_format($upg['original_price'], 0, ',', '.') }}</span>
                        <strong>Rp {{ number_format($upg['final_price'], 0, ',', '.') }}</strong>
                        <span class="discount-tag">Hemat {{ $upg['discount_percent'] }}%</span>
                    </p>
                @endif
            </div>
            <div class="upgrade-suggest-actions">
                <a href="{{ route('subscription.checkout', $sug) }}" class="btn-upgrade">Upgrade Sekarang</a>
                <form method="POST" action="{{ route('subscription.add-to-cart', $sug) }}">@csrf
                    <button type="submit" class="btn-upgrade-secondary"><i data-lucide="shopping-cart"></i> Keranjang</button>
                </form>
            </div>
        </div>
    @endif

    <h3 class="mysub-section-title">Riwayat</h3>
    <div class="mysub-list">
        @forelse ($subs as $sub)
            <div class="mysub-item">
                <div>
                    <strong>{{ $sub->plan->name ?? '-' }}</strong>
                    <p class="mysub-item-meta">{{ $sub->plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }} • {{ $sub->plan->formatted_price ?? '' }} • {{ $sub->created_at->format('d M Y') }}</p>
                </div>
                <span class="badge badge-{{ $sub->status === 'active' ? 'emerald' : ($sub->status === 'pending' ? 'amber' : 'slate') }}">{{ $sub->status_label }}</span>
            </div>
        @empty
            <p class="mysub-empty">Belum ada langganan.</p>
        @endforelse
    </div>
    <div>{{ $subs->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.mysub-shell { display: flex; flex-direction: column; gap: 18px; }
.mysub-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
.mysub-header h2 { margin: 0; color: #123122; }
.mysub-header-actions { display: flex; gap: 8px; align-items: center; }
.btn-link { color: #0f5a34; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-link i { width: 14px; height: 14px; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-success { background: #dcfce7; color: #166534; }

.active-card { background: linear-gradient(135deg, #0f5a34, #0a4326); color: #fff; border-radius: 18px; padding: 22px 26px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
.active-card-body { flex: 1; min-width: 240px; }
.active-card h3 { margin: 4px 0; }
.active-card p { margin: 4px 0; opacity: .9; }
.active-kicker { margin: 0; text-transform: uppercase; letter-spacing: .1em; font-size: 11px; opacity: .75; }
.active-meta { font-size: 12px; opacity: .8; }
.active-chip { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 4px 10px; background: rgba(255,255,255,.16); border-radius: 999px; font-size: 12px; font-weight: 600; }
.active-chip i { width: 14px; height: 14px; }
.active-chip-app { background: rgba(255,255,255,.1); }
.btn-danger-outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.5); padding: 10px 18px; border-radius: 10px; cursor: pointer; }
.btn-danger-outline:hover { background: rgba(255,255,255,.12); }

.upgrade-suggest { display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: center; background: linear-gradient(135deg, #fff7e6 0%, #fde7c1 100%); border: 1px solid #fcd34d; padding: 20px; border-radius: 18px; }
.upgrade-suggest-icon { width: 48px; height: 48px; border-radius: 14px; background: #b45309; color: #fff; display: flex; align-items: center; justify-content: center; }
.upgrade-suggest-icon i { width: 22px; height: 22px; }
.upgrade-suggest-body strong { color: #7c2d12; font-size: 15px; }
.upgrade-suggest-body p { margin: 4px 0; color: #92400e; font-size: 13px; }
.upgrade-suggest-deal { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.upgrade-suggest-deal .strike { text-decoration: line-through; color: #b45309; }
.upgrade-suggest-deal strong { color: #b45309; font-size: 20px; font-weight: 800; }
.discount-tag { padding: 2px 8px; border-radius: 999px; background: #b45309; color: #fff; font-size: 11px; font-weight: 700; }
.upgrade-suggest-actions { display: flex; flex-direction: column; gap: 6px; }
.btn-upgrade { display: inline-flex; align-items: center; justify-content: center; background: #b45309; color: #fff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 700; }
.btn-upgrade:hover { background: #92400e; }
.btn-upgrade-secondary { display: inline-flex; align-items: center; gap: 6px; background: #fff; color: #b45309; border: 1px solid #fcd34d; padding: 8px 14px; border-radius: 10px; font-weight: 600; cursor: pointer; font-family: inherit; }
.btn-upgrade-secondary:hover { background: #fef3c7; }
.btn-upgrade-secondary i { width: 14px; height: 14px; }
@media (max-width: 720px) { .upgrade-suggest { grid-template-columns: auto 1fr; } .upgrade-suggest-actions { grid-column: 1 / -1; flex-direction: row; } }

.mysub-section-title { color: #123122; margin-top: 8px; }
.mysub-list { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
.mysub-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-top: 1px solid #f1f5f9; }
.mysub-item:first-child { border-top: none; }
.mysub-item-meta { margin: 4px 0 0; color: #94a3b8; font-size: 12px; }
.badge { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-slate { background: #e2e8f0; color: #334155; }
.mysub-empty { text-align: center; color: #94a3b8; padding: 30px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
        document.querySelectorAll('[data-confirm]').forEach(function (f) {
            f.addEventListener('submit', function (e) { if (!confirm(f.dataset.confirm)) e.preventDefault(); });
        });
    });
</script>
@endpush