@extends('admin.layout')

@section('page-title', 'Detail Pembayaran #' . $transaction->id)

@section('admin-content')
<div class="admin-form-page">
    <a href="{{ route('admin.transactions.index') }}" class="btn-admin-text">← Kembali</a>

    <div class="admin-form-card">
        <div class="detail-head">
            <div>
                <h2>Pembayaran #{{ $transaction->id }}</h2>
                <p>Dibuat {{ $transaction->created_at->translatedFormat('d F Y H:i') }}</p>
            </div>
            <span class="badge {{ $transaction->status_badge_class }} badge-lg">{{ $transaction->status_label }}</span>
        </div>

        <div class="detail-grid">
            <div><span class="muted">Pengguna</span><strong>{{ $transaction->user->name ?? '—' }}</strong><small>{{ $transaction->user->email ?? 'Tanpa akun' }}</small></div>
            <div><span class="muted">Paket</span><strong>{{ $transaction->item_name }}</strong><small>{{ $transaction->category }}</small></div>
            <div><span class="muted">Jumlah</span><strong>Rp {{ number_format((int) $transaction->amount, 0, ',', '.') }}</strong></div>
            <div><span class="muted">Referensi Pembayaran</span><strong class="mono">{{ $transaction->payment_reference ?? '—' }}</strong></div>
            @if ($transaction->notes)
                <div class="full"><span class="muted">Catatan</span><strong>{{ $transaction->notes }}</strong></div>
            @endif
            @if ($transaction->cancelled_at)
                <div><span class="muted">Dibatalkan</span><strong>{{ $transaction->cancelled_at->translatedFormat('d F Y H:i') }}</strong><small>oleh {{ $transaction->cancelledBy->name ?? '—' }}</small></div>
                <div class="full"><span class="muted">Alasan</span><strong>{{ $transaction->cancellation_reason }}</strong></div>
            @endif
            @if ($transaction->refund_reference)
                <div><span class="muted">Refund Ref</span><strong class="mono">{{ $transaction->refund_reference }}</strong></div>
                <div><span class="muted">Nominal Refund</span><strong class="text-rose">Rp {{ number_format((int) $transaction->refund_amount, 0, ',', '.') }}</strong></div>
            @endif
        </div>
    </div>

    @if ($transaction->isCancellable())
        <div class="admin-form-card cancel-card">
            <h3>Batalkan / Refund Pembayaran</h3>
            <p class="muted">Tindakan ini akan mengubah status transaksi dan tidak dapat dibatalkan. Untuk membatalkan langganan terkait, kelola dari halaman Langganan.</p>

            <form method="POST" action="{{ route('admin.transactions.cancel', $transaction) }}" class="cancel-form" data-confirm="Yakin membatalkan transaksi ini?">
                @csrf
                <div class="form-row">
                    <label>Alasan pembatalan <span class="req">*</span></label>
                    <textarea name="cancellation_reason" required maxlength="500" class="admin-input" placeholder="Mis. Permintaan refund dari pelanggan, kesalahan input, dll.">{{ old('cancellation_reason') }}</textarea>
                </div>
                <div class="form-row">
                    <label class="switch">
                        <input type="checkbox" name="refund" value="1" @checked(old('refund', true))>
                        <span class="switch-slider"></span>
                        <span class="switch-label">Sertakan refund</span>
                    </label>
                </div>
                <div class="form-row">
                    <label>Nominal refund (Rp)</label>
                    <input type="number" name="refund_amount" min="0" max="{{ (int) $transaction->amount }}" value="{{ old('refund_amount', (int) $transaction->amount) }}" class="admin-input">
                    <small class="form-help">Maks: Rp {{ number_format((int) $transaction->amount, 0, ',', '.') }}. Kosongkan untuk refund penuh (default).</small>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-admin-danger"><i data-lucide="x-circle"></i> Proses Pembatalan</button>
                </div>
            </form>
        </div>
    @else
        <div class="admin-form-card">
            <h3>Status Terkunci</h3>
            <p class="muted">Transaksi ini sudah berstatus <strong>{{ $transaction->status_label }}</strong> dan tidak dapat dibatalkan lagi.</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.admin-form-page { max-width: 880px; display: flex; flex-direction: column; gap: 14px; }
.admin-form-card { background: #fff; border-radius: 18px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,.04); display: flex; flex-direction: column; gap: 16px; }
.admin-form-card h2 { margin: 0; color: #0f172a; }
.admin-form-card h3 { margin: 0; color: #0f172a; }
.detail-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; }
.detail-head p { margin: 4px 0 0; color: #64748b; font-size: 13px; }
.badge-lg { padding: 6px 14px; font-size: 12px; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-emerald { background: #d1fae5; color: #065f46; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-rose { background: #ffe4e6; color: #9f1239; }
.badge-slate { background: #e2e8f0; color: #334155; }
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.detail-grid > div { display: flex; flex-direction: column; gap: 2px; padding: 10px 12px; background: #f8fafc; border-radius: 10px; }
.detail-grid > div.full { grid-column: 1 / -1; }
.muted { color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; }
.text-rose { color: #be123c; }
.mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 13px; word-break: break-all; }
.btn-admin-text { color: #64748b; text-decoration: none; font-weight: 600; }

.cancel-card { border-color: #fecdd3; background: linear-gradient(180deg, #fff, #fff5f5); }
.cancel-form { display: flex; flex-direction: column; gap: 14px; }
.form-row { display: flex; flex-direction: column; gap: 6px; }
.form-row label { font-weight: 600; color: #1e293b; font-size: 14px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
.form-row textarea { min-height: 90px; resize: vertical; }
.form-help { color: #94a3b8; font-size: 12px; }
.req { color: #dc2626; }
.form-actions { display: flex; justify-content: flex-end; }
.btn-admin-danger { display: inline-flex; align-items: center; gap: 6px; background: #be123c; color: #fff; padding: 12px 20px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
.btn-admin-danger:hover { background: #9f1239; }
.btn-admin-danger i { width: 16px; height: 16px; }

.switch { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
.switch input { display: none; }
.switch-slider { width: 38px; height: 22px; background: #cbd5e1; border-radius: 999px; position: relative; transition: background .15s ease; }
.switch-slider::after { content: ''; position: absolute; top: 2px; left: 2px; width: 18px; height: 18px; background: #fff; border-radius: 50%; transition: transform .15s ease; box-shadow: 0 1px 3px rgba(0,0,0,.15); }
.switch input:checked + .switch-slider { background: #be123c; }
.switch input:checked + .switch-slider::after { transform: translateX(16px); }
.switch-label { font-weight: 600; color: #1e293b; font-size: 14px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();

    document.querySelectorAll('[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.dataset.confirm)) e.preventDefault();
        });
    });
});
</script>
@endpush