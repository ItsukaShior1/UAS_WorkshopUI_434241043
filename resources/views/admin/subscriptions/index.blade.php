@extends('admin.layout')

@section('page-title', 'Monitor Langganan')

@section('admin-content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Langganan Pengguna</h2>
            <p>Pantau status langganan semua pengguna Bookify.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="admin-filter-bar">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama/email..." class="admin-input">
        <select name="status" class="admin-input">
            <option value="">Semua status</option>
            <option value="active" @selected($status === 'active')>Aktif</option>
            <option value="pending" @selected($status === 'pending')>Menunggu</option>
            <option value="expired" @selected($status === 'expired')>Kedaluwarsa</option>
            <option value="cancelled" @selected($status === 'cancelled')>Dibatalkan</option>
        </select>
        <button class="btn-admin-text" type="submit">Cari</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Pengguna</th><th>Paket</th><th>Status</th><th>Berakhir</th><th>Pembayaran</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($subs as $sub)
                    <tr>
                        <td>
                            <div class="admin-cell-stack">
                                <strong>{{ $sub->user->name ?? '-' }}</strong>
                                <small class="admin-muted">{{ $sub->user->email ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            {{ $sub->plan->name ?? '-' }}
                            <small class="admin-muted"> ({{ $sub->plan->billing_period ?? '-' }})</small>
                        </td>
                        <td>
                            @php
                                $cls = match($sub->status) {
                                    'active' => 'badge-emerald',
                                    'pending' => 'badge-amber',
                                    'expired', 'cancelled', 'failed' => 'badge-slate',
                                    default => 'badge-slate',
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $sub->status_label }}</span>
                        </td>
                        <td>{{ optional($sub->ends_at)->format('d M Y H:i') ?: '-' }}</td>
                        <td>
                            <small class="admin-muted">{{ strtoupper($sub->payment_method ?? '-') }}</small><br>
                            <small>{{ $sub->formatted_price ?? ($sub->amount_paid ? 'Rp '.number_format($sub->amount_paid,0,',','.') : '-') }}</small>
                        </td>
                        <td><a href="{{ route('admin.subscriptions.show', $sub) }}" class="btn-icon" title="Detail"><i data-lucide="eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="admin-empty">Belum ada langganan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="admin-pagination">{{ $subs->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.admin-page { display: flex; flex-direction: column; gap: 18px; }
.admin-page-header h2 { margin: 0; color: #0f172a; }
.admin-page-header p { margin: 4px 0 0; color: #64748b; font-size: 13px; }
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
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; }
.btn-icon:hover { background: #f1f5f9; }
.btn-icon i { width: 14px; height: 14px; color: #475569; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-slate { background: #e2e8f0; color: #334155; }
.admin-empty { text-align: center; padding: 40px; color: #94a3b8; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.admin-pagination { margin-top: 6px; }
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
