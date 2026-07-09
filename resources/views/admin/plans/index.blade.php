@extends('admin.layout')

@section('page-title', 'Manajemen Paket')

@section('admin-content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Paket Langganan</h2>
            <p>Kelola paket aplikasi dan marketplace yang ditawarkan ke pengguna.</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn-admin-primary">
            <i data-lucide="plus"></i> Tambah Paket
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="GET" class="admin-filter-bar">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama paket..." class="admin-input">
        <select name="period" class="admin-input">
            <option value="">Semua periode</option>
            <option value="monthly" @selected($period === 'monthly')>Bulanan</option>
            <option value="yearly" @selected($period === 'yearly')>Tahunan</option>
        </select>
        <button class="btn-admin-text" type="submit">Cari</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Paket</th>
                    <th>Periode</th>
                    <th>Harga</th>
                    <th>Marketplace</th>
                    <th>Diskon Upgrade</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr>
                        <td>
                            <div class="admin-cell-stack">
                                <strong>{{ $plan->name }}</strong>
                                <small class="admin-muted">Kode: {{ $plan->code }}</small>
                            </div>
                        </td>
                        <td>{{ $plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }}</td>
                        <td><strong>{{ $plan->formatted_price }}</strong></td>
                        <td>
                            @if ($plan->includes_marketplace)
                                <span class="badge badge-emerald">Termasuk</span>
                            @else
                                <span class="badge badge-slate">Tidak</span>
                            @endif
                        </td>
                        <td>
                            @if ($plan->includes_marketplace)
                                <span class="badge badge-amber">{{ $plan->upgrade_discount_percent ?: \App\Models\Plan::DEFAULT_UPGRADE_DISCOUNT_PERCENT }}%</span>
                            @else
                                <small class="admin-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @if ($plan->is_active)
                                <span class="badge badge-emerald">Aktif</span>
                            @else
                                <span class="badge badge-amber">Non-aktif</span>
                            @endif
                        </td>
                        <td class="admin-actions">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}" class="inline-form">@csrf
                                <button class="btn-icon" type="submit" title="Toggle aktif"><i data-lucide="power"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline-form" data-confirm="Hapus paket ini?">@csrf @method('DELETE')
                                <button class="btn-icon btn-icon-danger" type="submit" title="Hapus"><i data-lucide="trash-2"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="admin-empty">Belum ada paket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $plans->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.admin-page { display: flex; flex-direction: column; gap: 18px; }
.admin-page-header { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
.admin-page-header h2 { margin: 0; color: #0f172a; }
.admin-page-header p { margin: 4px 0 0; color: #64748b; font-size: 13px; }
.btn-admin-primary { display: inline-flex; align-items: center; gap: 6px; background: #0f172a; color: #fff; padding: 10px 16px; border-radius: 12px; text-decoration: none; font-weight: 600; }
.btn-admin-primary:hover { background: #1e293b; }
.btn-admin-primary i { width: 16px; height: 16px; }
.admin-filter-bar { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 14px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 14px; }
.admin-table-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th, .admin-table td { text-align: left; padding: 12px 14px; font-size: 14px; }
.admin-table thead { background: #f8fafc; }
.admin-table tbody tr { border-top: 1px solid #f1f5f9; }
.admin-table tbody tr:hover { background: #f8fafc; }
.admin-cell-stack { display: flex; flex-direction: column; gap: 2px; }
.admin-muted { color: #94a3b8; font-size: 12px; }
.admin-actions { display: flex; gap: 6px; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; }
.btn-icon:hover { background: #f1f5f9; }
.btn-icon i { width: 14px; height: 14px; color: #475569; }
.btn-icon-danger { color: #dc2626; }
.inline-form { display: inline; margin: 0; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-slate { background: #e2e8f0; color: #334155; }
.admin-empty { text-align: center; padding: 40px; color: #94a3b8; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-alert ul { margin: 0; padding-left: 18px; }
.admin-pagination { margin-top: 6px; }
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
