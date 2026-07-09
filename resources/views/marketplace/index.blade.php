@extends('layouts.dashboard')

@section('page-title', 'Integrasi Marketplace')

@section('dashboard-content')
<div class="mk-shell">
    <div class="mk-header">
        <div>
            <h2>Marketplace</h2>
            <p>Hubungkan toko Shopee, Tokopedia, dan Lazada Anda untuk sinkronisasi otomatis.</p>
        </div>
        @if ($hasAccess)
            <a href="{{ route('marketplace.create') }}" class="btn-primary"><i data-lucide="plus"></i> Hubungkan</a>
        @endif
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-error"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    @if (! $hasAccess)
        <div class="locked-banner">
            <i data-lucide="lock"></i>
            <div>
                <strong>Fitur ini membutuhkan paket Marketplace</strong>
                <p>Upgrade paket Anda untuk menghubungkan akun Shopee, Tokopedia, dan Lazada.</p>
            </div>
            <a href="{{ route('subscription.index') }}" class="btn-primary">Lihat Paket</a>
        </div>
    @endif

    <div class="mk-grid">
        @forelse ($integrations as $i)
            <div class="mk-card">
                <div class="mk-card-head">
                    <span class="mk-platform mk-platform-{{ $i->platform }}">{{ $i->platform_label }}</span>
                    <span class="badge {{ $i->is_active ? 'badge-emerald' : 'badge-slate' }}">{{ $i->is_active ? 'Aktif' : 'Non-aktif' }}</span>
                </div>
                <h3>{{ $i->shop_name ?: 'Toko '.$i->platform_label }}</h3>
                <p class="mk-meta">API Key: <code>{{ $i->masked_key }}</code></p>
                <p class="mk-meta">Terhubung: {{ optional($i->connected_at)->format('d M Y') ?: '-' }}</p>
                <div class="mk-card-actions">
                    <form method="POST" action="{{ route('marketplace.toggle', $i) }}">@csrf
                        <button class="btn-text" type="submit">{{ $i->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                    <form method="POST" action="{{ route('marketplace.destroy', $i) }}" data-confirm="Hapus integrasi ini?">@csrf @method('DELETE')
                        <button class="btn-text-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="mk-empty">
                <i data-lucide="store"></i>
                <p>Belum ada marketplace terhubung.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.mk-shell { display: flex; flex-direction: column; gap: 18px; }
.mk-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
.mk-header h2 { margin: 0; color: #123122; }
.mk-header p { margin: 4px 0 0; color: #54685f; font-size: 13px; }
.btn-primary { display: inline-flex; align-items: center; gap: 6px; background: #0f5a34; color: #fff; padding: 10px 16px; border-radius: 12px; text-decoration: none; font-weight: 600; }
.btn-primary i { width: 16px; height: 16px; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-success { background: #dcfce7; color: #166534; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert ul { margin: 0; padding-left: 18px; }
.locked-banner { display: flex; align-items: center; gap: 16px; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 14px; padding: 18px 20px; }
.locked-banner i { width: 28px; height: 28px; color: #c2410c; }
.locked-banner strong { color: #7c2d12; }
.locked-banner p { margin: 4px 0 0; color: #9a3412; font-size: 13px; }
.mk-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px; }
.mk-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 6px; }
.mk-card-head { display: flex; justify-content: space-between; align-items: center; }
.mk-platform { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; color: #fff; }
.mk-platform-shopee { background: #ee4d2d; }
.mk-platform-tokopedia { background: #03ac0e; }
.mk-platform-lazada { background: #0f146d; }
.mk-card h3 { margin: 0; color: #123122; font-size: 16px; }
.mk-meta { margin: 0; color: #5d7d6f; font-size: 13px; }
.mk-meta code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
.badge { padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-slate { background: #e2e8f0; color: #334155; }
.mk-card-actions { display: flex; gap: 12px; margin-top: 8px; }
.btn-text { background: none; border: none; color: #0f5a34; font-weight: 600; cursor: pointer; }
.btn-text-danger { background: none; border: none; color: #dc2626; font-weight: 600; cursor: pointer; }
.mk-empty { grid-column: 1 / -1; text-align: center; padding: 40px; color: #94a3b8; background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; }
.mk-empty i { width: 48px; height: 48px; margin-bottom: 8px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
        document.querySelectorAll('[data-confirm]').forEach(f => f.addEventListener('submit', e => { if (!confirm(f.dataset.confirm)) e.preventDefault(); }));
    });
</script>
@endpush
