@extends('layouts.dashboard')

@section('page-title', 'Pembayaran - ' . $plan->name)

@section('dashboard-content')
<div class="checkout-shell">
    <a href="{{ route('subscription.index') }}" class="btn-link">&larr; Kembali</a>

    @if ($upgrade)
        <div class="upgrade-banner-inline">
            <div class="upgrade-banner-icon"><i data-lucide="zap"></i></div>
            <div>
                <strong>Diskon Upgrade {{ $upgrade['discount_percent'] }}% terdeteksi otomatis</strong>
                <p>Karena Anda pelanggan setia Bookify App, sistem menerapkan potongan harga upgrade ke paket Marketplace.</p>
            </div>
        </div>
    @endif

    <div class="checkout-grid">
        <div class="checkout-summary">
            <h2>Ringkasan Pesanan</h2>
            <div class="summary-row"><span>Paket</span><strong>{{ $plan->name }}</strong></div>
            <div class="summary-row"><span>Periode</span><strong>{{ $plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan' }}</strong></div>
            <div class="summary-row"><span>Berlaku hingga</span><strong>{{ $plan->billing_period === 'yearly' ? now()->addYear()->translatedFormat('d F Y') : now()->addMonth()->translatedFormat('d F Y') }}</strong></div>
            <hr>
            @if ($upgrade)
                <div class="summary-row"><span>Harga normal</span><span class="strike">Rp {{ number_format($upgrade['original_price'], 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Diskon ({{ $upgrade['discount_percent'] }}%)</span><span class="discount">- Rp {{ number_format($upgrade['discount_amount'], 0, ',', '.') }}</span></div>
            @endif
            <div class="summary-row total">
                <span>Total</span>
                <strong>Rp {{ number_format($upgrade ? $upgrade['final_price'] : $plan->price, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="checkout-payment">
            <h2>Pilih Metode Pembayaran</h2>
            <p class="muted">Simulasi Midtrans - tidak ada transaksi nyata.</p>

            <div class="pay-tabs">
                <button class="pay-tab active" data-tab="ewallet"><i data-lucide="smartphone"></i> E-Wallet</button>
                <button class="pay-tab" data-tab="va"><i data-lucide="building-2"></i> Virtual Account</button>
                <button class="pay-tab" data-tab="qris"><i data-lucide="qr-code"></i> QRIS</button>
            </div>

            <form method="POST" action="{{ route('subscription.pay', $plan) }}" class="pay-form">
                @csrf
                <input type="hidden" name="payment_method" value="ewallet">

                <div class="pay-panel" data-panel="ewallet">
                    <label>Pilih E-Wallet</label>
                    <select name="payment_channel" class="admin-input">
                        <option value="gopay">GoPay</option>
                        <option value="ovo">OVO</option>
                        <option value="dana">DANA</option>
                        <option value="shopeepay">ShopeePay</option>
                    </select>
                    <p class="muted">Anda akan diarahkan ke aplikasi dompet digital.</p>
                </div>

                <div class="pay-panel" data-panel="va" style="display:none;">
                    <label>Pilih Bank</label>
                    <select name="payment_channel" class="admin-input">
                        <option value="bca">BCA</option>
                        <option value="bni">BNI</option>
                        <option value="bri">BRI</option>
                        <option value="mandiri">Mandiri</option>
                    </select>
                    <p class="muted">Nomor VA akan diterbitkan setelah klik Bayar.</p>
                </div>

                <div class="pay-panel" data-panel="qris" style="display:none;">
                    <p>Scan QRIS berikut untuk membayar:</p>
                    <div class="qris-box">
                        @php
                            $qrPayload = 'BOOKIFY|'.$plan->id.'|'.($upgrade ? $upgrade['final_price'] : $plan->price).'|'.now()->timestamp;
                        @endphp
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrPayload) }}" alt="QRIS">
                        <p>Total: <strong>Rp {{ number_format($upgrade ? $upgrade['final_price'] : $plan->price, 0, ',', '.') }}</strong></p>
                    </div>
                </div>

                <button type="submit" class="btn-pay">Bayar Rp {{ number_format($upgrade ? $upgrade['final_price'] : $plan->price, 0, ',', '.') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.checkout-shell { display: flex; flex-direction: column; gap: 16px; }
.btn-link { color: #0f5a34; font-weight: 600; text-decoration: none; }

.upgrade-banner-inline { display: flex; gap: 14px; align-items: center; padding: 16px 20px; background: linear-gradient(135deg, #fff7e6, #fde7c1); border: 1px solid #fcd34d; border-radius: 14px; }
.upgrade-banner-inline .upgrade-banner-icon { width: 44px; height: 44px; border-radius: 12px; background: #b45309; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.upgrade-banner-inline .upgrade-banner-icon i { width: 22px; height: 22px; }
.upgrade-banner-inline strong { color: #7c2d12; }
.upgrade-banner-inline p { margin: 4px 0 0; color: #92400e; font-size: 13px; }

.checkout-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 16px; }
@media (max-width: 800px) { .checkout-grid { grid-template-columns: 1fr; } }
.checkout-summary, .checkout-payment { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; }
.checkout-summary h2, .checkout-payment h2 { margin: 0 0 14px; color: #123122; }
.summary-row { display: flex; justify-content: space-between; padding: 8px 0; gap: 8px; }
.summary-row.total { font-size: 18px; color: #0f5a34; }
.summary-row .strike { text-decoration: line-through; color: #94a3b8; }
.summary-row .discount { color: #d97706; font-weight: 700; }
.muted { color: #94a3b8; font-size: 13px; }
.pay-tabs { display: flex; gap: 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px; }
.pay-tab { display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; background: #f1f5f9; border: 1px solid transparent; border-radius: 10px; cursor: pointer; font-weight: 600; color: #475569; }
.pay-tab.active { background: #0f5a34; color: #fff; }
.pay-tab i { width: 16px; height: 16px; }
.pay-form { display: flex; flex-direction: column; gap: 14px; }
.pay-panel { display: flex; flex-direction: column; gap: 8px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; }
.qris-box { display: flex; flex-direction: column; align-items: center; gap: 8px; background: #f8fafc; border-radius: 14px; padding: 16px; }
.qris-box img { background: #fff; padding: 8px; border-radius: 10px; }
.btn-pay { background: #0f5a34; color: #fff; padding: 14px; border-radius: 12px; border: none; font-weight: 700; font-size: 15px; cursor: pointer; }
.btn-pay:hover { background: #0a4326; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
        const tabs = document.querySelectorAll('.pay-tab');
        const panels = document.querySelectorAll('.pay-panel');
        const method = document.querySelector('input[name="payment_method"]');
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const id = tab.dataset.tab;
                method.value = id;
                panels.forEach(p => p.style.display = p.dataset.panel === id ? 'flex' : 'none');
            });
        });
    });
</script>
@endpush
