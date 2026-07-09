@extends('admin.layout')

@section('page-title', 'Detail Langganan #' . $subscription->id)

@section('admin-content')
<div class="admin-form-page">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-admin-text">Kembali</a>
    <div class="admin-form-card">
        <h2>Langganan #{{ $subscription->id }}</h2>
        <div class="detail-grid">
            <div><span class="muted">Pengguna</span><strong>{{ $subscription->user->name ?? '-' }}</strong><small>{{ $subscription->user->email ?? '-' }}</small></div>
            <div><span class="muted">Paket</span><strong>{{ $subscription->plan->name ?? '-' }}</strong><small>{{ $subscription->plan->billing_period ?? '' }} - {{ $subscription->plan->formatted_price ?? '' }}</small></div>
            <div><span class="muted">Status</span><strong>{{ $subscription->status_label }}</strong></div>
            <div><span class="muted">Mulai</span><strong>{{ optional($subscription->starts_at)->format('d M Y H:i') ?: '-' }}</strong></div>
            <div><span class="muted">Berakhir</span><strong>{{ optional($subscription->ends_at)->format('d M Y H:i') ?: '-' }}</strong></div>
            <div><span class="muted">Metode</span><strong>{{ $subscription->payment_method ?: '-' }}</strong><small>Ref: {{ $subscription->payment_reference ?: '-' }}</small></div>
            <div><span class="muted">Jumlah</span><strong>Rp {{ number_format((float) $subscription->amount_paid, 0, ',', '.') }}</strong></div>
            <div><span class="muted>Dibuat</span><strong>{{ $subscription->created_at->format('d M Y H:i') }}</strong></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-form-page { max-width: 800px; display: flex; flex-direction: column; gap: 14px; }
.admin-form-card { background: #fff; border-radius: 18px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,.04); }
.admin-form-card h2 { margin-top: 0; color: #0f172a; }
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.detail-grid > div { display: flex; flex-direction: column; gap: 2px; padding: 10px 12px; background: #f8fafc; border-radius: 10px; }
.muted { color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; }
.btn-admin-text { color: #64748b; text-decoration: none; font-weight: 600; }
</style>
@endpush
