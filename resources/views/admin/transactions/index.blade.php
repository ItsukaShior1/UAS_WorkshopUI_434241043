@extends('admin.layout')

@section('page-title', 'Transaksi Pembayaran Langganan')

@section('admin-content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Transaksi Pembayaran Langganan</h2>
            <p>Kelola pembayaran paket langganan seluruh pengguna Bookify, termasuk pembatalan dan refund.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card stat-emerald">
            <span class="stat-label">Total Pembayaran</span>
            <strong class="stat-value">{{ number_format($summary['total']) }}</strong>
            <small class="stat-hint">{{ $summary['completed'] }} berhasil</small>
        </div>
        <div class="stat-card stat-rose">
            <span class="stat-label">Dibatalkan</span>
            <strong class="stat-value">{{ number_format($summary['cancelled']) }}</strong>
        </div>
        <div class="stat-card stat-amber">
            <span class="stat-label">Direfund</span>
            <strong class="stat-value">{{ number_format($summary['refunded']) }}</strong>
            <small class="stat-hint">Total refund: Rp {{ number_format($summary['refund_total'], 0, ',', '.') }}</small>
        </div>
        <div class="stat-card stat-slate">
            <span class="stat-label">Pendapatan (Berhasil)</span>
            <strong class="stat-value">Rp {{ number_format($summary['revenue_total'], 0, ',', '.') }}</strong>
        </div>
    </div>

    <form method="GET" class="admin-filter-bar">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari item, referensi, atau pengguna..." class="admin-input admin-input-grow">
        <select name="status" class="admin-input">
            <option value="">Semua status</option>
            <option value="completed" @selected($status === 'completed')>Berhasil</option>
            <option value="cancelled" @selected($status === 'cancelled')>Dibatalkan</option>
            <option value="refunded" @selected($status === 'refunded')>Direfund</option>
        </select>
        <button class="btn-admin-text" type="submit">Cari</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pengguna</th>
                    <th>Paket</th>
                    <th>Referensi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                    <tr>
                        <td>
                            <div class="admin-cell-stack">
                                <strong>{{ $trx->created_at->format('d M Y') }}</strong>
                                <small class="admin-muted">{{ $trx->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="admin-cell-stack">
                                <strong>{{ $trx->user->name ?? '—' }}</strong>
                                <small class="admin-muted">{{ $trx->user->email ?? 'Tanpa pengguna' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="admin-cell-stack">
                                <strong>{{ $trx->item_name }}</strong>
                                <small class="admin-muted">{{ $trx->category }}</small>
                            </div>
                        </td>
                        <td><small class="admin-muted">{{ $trx->payment_reference ?? $trx->refund_reference ?? '—' }}</small></td>
                        <td><strong>Rp {{ number_format((int) $trx->amount, 0, ',', '.') }}</strong></td>
                        <td><span class="badge {{ $trx->status_badge_class }}">{{ $trx->status_label }}</span></td>
                        <td class="admin-actions">
                            <a href="{{ route('admin.transactions.show', $trx) }}" class="btn-icon" title="Detail & kelola"><i data-lucide="settings-2"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="admin-empty">Belum ada transaksi pembayaran langganan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $transactions->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.admin-page { display: flex; flex-direction: column; gap: 18px; }
.admin-page-header { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
.admin-page-header h2 { margin: 0; color: #0f172a; }
.admin-page-header p { margin: 4px 0 0; color: #64748b; font-size: 13px; }
.admin-filter-bar { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 14px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 14px; }
.admin-input-grow { flex: 1; min-width: 220px; }
.btn-admin-text { color: #0f5a34; font-weight: 600; background: none; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 10px; cursor: pointer; }
.btn-admin-text:hover { background: #f1f5f9; }
.admin-table-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th, .admin-table td { text-align: left; padding: 12px 14px; font-size: 14px; }
.admin-table thead { background: #f8fafc; }
.admin-table tbody tr { border-top: 1px solid #f1f5f9; }
.admin-table tbody tr:hover { background: #f8fafc; }
.admin-cell-stack { display: flex; flex-direction: column; gap: 2px; }
.admin-muted { color: #94a3b8; font-size: 12px; }
.admin-actions { display: flex; gap: 6px; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; }
.btn-icon:hover { background: #f1f5f9; }
.btn-icon i { width: 14px; height: 14px; color: #475569; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-rose { background: #ffe4e6; color: #9f1239; }
.badge-slate { background: #e2e8f0; color: #334155; }
.admin-empty { text-align: center; padding: 40px; color: #94a3b8; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-alert ul { margin: 0; padding-left: 18px; }
.admin-pagination { margin-top: 6px; }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
.stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; display: flex; flex-direction: column; gap: 4px; border-left: 4px solid #cbd5e1; }
.stat-card.stat-emerald { border-left-color: #10b981; }
.stat-card.stat-rose { border-left-color: #f43f5e; }
.stat-card.stat-amber { border-left-color: #f59e0b; }
.stat-card.stat-slate { border-left-color: #64748b; }
.stat-label { color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
.stat-value { color: #0f172a; font-size: 22px; font-weight: 700; word-break: break-word; }
.stat-hint { color: #94a3b8; font-size: 12px; }
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