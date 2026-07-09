@extends('layouts.dashboard')

@section('page-title', 'Pembayaran Berhasil')

@section('dashboard-content')
<div class="cart-success-shell">
    <div class="cart-success-card">
        <i data-lucide="circle-check" class="success-icon"></i>
        <h2>Pembayaran Berhasil</h2>
        <p class="muted">Terima kasih! Pesanan Anda telah kami terima.</p>
        <div class="summary-row"><span>Referensi</span><strong>{{ $reference }}</strong></div>
        <div class="summary-row"><span>Metode</span><strong>{{ strtoupper($paymentMethod) }}</strong></div>
        <div class="summary-row total"><span>Total</span><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></div>
        <a href="{{ route('dashboard.home') }}" class="btn-primary">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-success-shell { display: flex; justify-content: center; padding: 24px 0; }
.cart-success-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 36px; max-width: 440px; width: 100%; text-align: center; display: flex; flex-direction: column; gap: 12px; align-items: center; }
.success-icon { width: 64px; height: 64px; color: #16a34a; }
.cart-success-card h2 { margin: 0; color: #123122; }
.muted { color: #5d7d6f; margin: 0; }
.summary-row { display: flex; justify-content: space-between; width: 100%; padding: 4px 0; }
.summary-row.total { font-size: 18px; color: #0f5a34; font-weight: 700; }
.btn-primary { background: #0f5a34; color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; margin-top: 8px; }
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
