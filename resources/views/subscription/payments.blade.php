@extends('layouts.dashboard')

@section('page-title', 'Riwayat Pembayaran Langganan')

@section('dashboard-content')
<div class="pay-shell">
    <div class="pay-header">
        <div>
            <h2>Riwayat Pembayaran Langganan</h2>
            <p>Daftar semua transaksi pembayaran paket langganan Anda.</p>
        </div>
        <a href="{{ route('subscription.my') }}" class="btn-link">&larr; Kembali ke langganan saya</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="pay-stats">
        <div class="pay-stat">
            <span class="pay-stat-label">Total Pembayaran</span>
            <strong>{{ number_format($summary['total']) }}</strong>
        </div>
        <div class="pay-stat pay-stat-emerald">
            <span class="pay-stat-label">Berhasil</span>
            <strong>{{ number_format($summary['completed']) }}</strong>
        </div>
        <div class="pay-stat pay-stat-rose">
            <span class="pay-stat-label">Dibatalkan</span>
            <strong>{{ number_format($summary['cancelled']) }}</strong>
        </div>
        <div class="pay-stat pay-stat-amber">
            <span class="pay-stat-label">Direfund</span>
            <strong>{{ number_format($summary['refunded']) }}</strong>
        </div>
        <div class="pay-stat pay-stat-primary">
            <span class="pay-stat-label">Total Dibayar</span>
            <strong>Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</strong>
        </div>
        <div class="pay-stat pay-stat-secondary">
            <span class="pay-stat-label">Total Refund</span>
            <strong>Rp {{ number_format($summary['total_refunded'], 0, ',', '.') }}</strong>
        </div>
    </div>

    <form method="GET" class="pay-filter">
        <select name="status" class="admin-input">
            <option value="">Semua status</option>
            <option value="completed" @selected($status === 'completed')>Berhasil</option>
            <option value="cancelled" @selected($status === 'cancelled')>Dibatalkan</option>
            <option value="refunded" @selected($status === 'refunded')>Direfund</option>
        </select>
        <button class="btn-filter" type="submit">Filter</button>
    </form>

    <div class="pay-list">
        @forelse ($payments as $pay)
            <div class="pay-item">
                <div class="pay-item-icon">
                    @if ($pay->status === \App\Models\Transaction::STATUS_COMPLETED)
                        <i data-lucide="check-circle-2"></i>
                    @elseif ($pay->status === \App\Models\Transaction::STATUS_REFUNDED)
                        <i data-lucide="rotate-ccw"></i>
                    @else
                        <i data-lucide="x-circle"></i>
                    @endif
                </div>
                <div class="pay-item-body">
                    <div class="pay-item-head">
                        <strong>{{ $pay->item_name }}</strong>
                        <span class="badge badge-{{ $pay->status === \App\Models\Transaction::STATUS_COMPLETED ? 'emerald' : ($pay->status === \App\Models\Transaction::STATUS_REFUNDED ? 'amber' : 'rose') }}">{{ $pay->status_label }}</span>
                    </div>
                    <p class="pay-item-meta">
                        <span><i data-lucide="calendar"></i> {{ $pay->created_at->translatedFormat('d F Y H:i') }}</span>
                        <span><i data-lucide="hash"></i> {{ $pay->payment_reference ?? '—' }}</span>
                    </p>
                    @if ($pay->notes)
                        <p class="pay-item-notes">{{ $pay->notes }}</p>
                    @endif
                    @if ($pay->cancelled_at)
                        <div class="pay-item-cancel">
                            <strong>Dibatalkan</strong> {{ $pay->cancelled_at->translatedFormat('d F Y H:i') }}@if($pay->cancelledBy) oleh {{ $pay->cancelledBy->name }}@endif
                            @if($pay->cancellation_reason) — <em>"{{ $pay->cancellation_reason }}"</em>@endif
                        </div>
                    @endif
                    @if ($pay->refund_reference)
                        <div class="pay-item-refund">
                            <strong>Refund</strong> Rp {{ number_format((int) $pay->refund_amount, 0, ',', '.') }}
                            <small class="mono">({{ $pay->refund_reference }})</small>
                        </div>
                    @endif
                </div>
                <div class="pay-item-amount">
                    <strong>Rp {{ number_format((int) $pay->amount, 0, ',', '.') }}</strong>
                    @if ((int) $pay->refund_amount > 0)
                        <small class="pay-item-amount-refund">-Rp {{ number_format((int) $pay->refund_amount, 0, ',', '.') }}</small>
                    @endif
                </div>
            </div>
        @empty
            <div class="pay-empty">
                <i data-lucide="receipt"></i>
                <h3>Belum ada pembayaran</h3>
                <p>Anda belum pernah melakukan pembayaran paket langganan.</p>
                <a href="{{ route('subscription.index') }}" class="btn-link-primary">Lihat Paket</a>
            </div>
        @endforelse
    </div>

    <div class="pay-pagination">{{ $payments->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.pay-shell { display: flex; flex-direction: column; gap: 18px; }
.pay-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
.pay-header h2 { margin: 0; color: #123122; }
.pay-header p { margin: 4px 0 0; color: #54685f; font-size: 13px; }
.btn-link { color: #0f5a34; font-weight: 600; text-decoration: none; padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 10px; }
.btn-link:hover { background: #f1f5f9; }
.btn-link-primary { display: inline-flex; align-items: center; gap: 6px; background: #0f5a34; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; }
.btn-link-primary:hover { background: #0a4326; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-success { background: #dcfce7; color: #166534; }

.pay-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; }
.pay-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; display: flex; flex-direction: column; gap: 4px; border-left: 4px solid #cbd5e1; }
.pay-stat strong { color: #123122; font-size: 18px; font-weight: 700; }
.pay-stat-label { color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
.pay-stat-emerald { border-left-color: #10b981; }
.pay-stat-rose { border-left-color: #f43f5e; }
.pay-stat-amber { border-left-color: #f59e0b; }
.pay-stat-primary { border-left-color: #0f5a34; background: #f0fdf4; }
.pay-stat-secondary { border-left-color: #b45309; background: #fef3c7; }

.pay-filter { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 14px; }
.btn-filter { background: #0f5a34; color: #fff; padding: 8px 16px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-family: inherit; }
.btn-filter:hover { background: #0a4326; }

.pay-list { display: flex; flex-direction: column; gap: 10px; }
.pay-item { display: grid; grid-template-columns: 48px 1fr auto; gap: 16px; align-items: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; transition: border-color .15s ease, box-shadow .15s ease; }
.pay-item:hover { border-color: #cbd5e1; box-shadow: 0 2px 10px rgba(15,23,42,.04); }
.pay-item-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #dcfce7; color: #166534; }
.pay-item-icon i { width: 22px; height: 22px; }
.pay-item-icon .lucide-rotate-ccw { color: #92400e; }
.pay-item-icon .lucide-x-circle { color: #9f1239; }
.pay-item-body { min-width: 0; }
.pay-item-head { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.pay-item-head strong { color: #123122; font-size: 15px; }
.badge { padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-rose { background: #ffe4e6; color: #9f1239; }
.pay-item-meta { margin: 4px 0 0; color: #94a3b8; font-size: 12px; display: flex; gap: 14px; flex-wrap: wrap; }
.pay-item-meta span { display: inline-flex; align-items: center; gap: 4px; }
.pay-item-meta i { width: 12px; height: 12px; }
.pay-item-notes { margin: 6px 0 0; color: #475569; font-size: 13px; font-style: italic; }
.pay-item-cancel { margin-top: 8px; padding: 8px 10px; background: #fef2f2; border-left: 3px solid #f43f5e; border-radius: 6px; color: #7f1d1d; font-size: 12px; }
.pay-item-refund { margin-top: 8px; padding: 8px 10px; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 6px; color: #78350f; font-size: 13px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.pay-item-refund .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 11px; word-break: break-all; }
.pay-item-amount { text-align: right; color: #123122; display: flex; flex-direction: column; align-items: flex-end; gap: 2px; }
.pay-item-amount strong { font-size: 18px; }
.pay-item-amount-refund { color: #d97706; font-size: 12px; font-weight: 700; }

.pay-empty { background: #fff; border: 1px dashed #cbd5e1; border-radius: 18px; padding: 50px 20px; text-align: center; color: #94a3b8; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.pay-empty i { width: 48px; height: 48px; color: #cbd5e1; }
.pay-empty h3 { margin: 0; color: #123122; }
.pay-empty p { margin: 0; }

.pay-pagination { margin-top: 6px; }

@media (max-width: 640px) {
    .pay-item { grid-template-columns: 40px 1fr; }
    .pay-item-amount { grid-column: 1 / -1; text-align: left; flex-direction: row; gap: 8px; }
}
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