@extends('layouts.dashboard')

@section('page-title', 'Detail Stok')

@section('dashboard-content')
@php
    $isCritical = $productData['status'] === 'critical';
    $isWarning = $productData['status'] === 'warning';
@endphp

<div class="stock-detail-page">
    <a href="{{ route('dashboard.stock') }}" class="stock-back-link">← Kembali ke Stok</a>

    <div class="stock-detail-hero {{ $productData['status'] }}">
        <div class="stock-detail-image-wrap">
            <img src="{{ $productData['image_url'] ?? asset('img/features/stock-management.jpg') }}" alt="{{ $productData['name'] }}" class="stock-detail-image">
        </div>
        <div class="stock-detail-main">
            <div class="stock-detail-topline">
                <span class="stock-detail-badge {{ $productData['status'] }}">{{ $statusLabel }}</span>
                <span class="stock-detail-code">ID #{{ $productData['id'] }}</span>
            </div>
            <h2>{{ $productData['name'] }}</h2>
            <p class="stock-detail-copy">Halaman ini menampilkan kondisi stok saat ini dan riwayat transaksi yang mempengaruhi barang ini.</p>

            <div class="stock-detail-stats">
                <article>
                    <span>Stok Saat Ini</span>
                    <strong>{{ $productData['stock'] }} unit</strong>
                </article>
                <article>
                    <span>Batas Minimum</span>
                    <strong>{{ $productData['min'] }} unit</strong>
                </article>
                <article>
                    <span>Harga Satuan</span>
                    <strong>Rp {{ number_format($productData['price'], 0, ',', '.') }}</strong>
                </article>
                <article>
                    <span>Nilai Stok</span>
                    <strong>Rp {{ number_format($productData['stock'] * $productData['price'], 0, ',', '.') }}</strong>
                </article>
            </div>
        </div>
    </div>

    <div class="stock-detail-summary-grid">
        <article class="stock-detail-summary-card">
            <span>Riwayat Transaksi</span>
            <strong>{{ $summary['transactions_count'] }}</strong>
        </article>
        <article class="stock-detail-summary-card success">
            <span>Barang Keluar</span>
            <strong>{{ $summary['stock_moved_out'] }} unit</strong>
        </article>
        <article class="stock-detail-summary-card warning">
            <span>Barang Masuk</span>
            <strong>{{ $summary['stock_moved_in'] }} unit</strong>
        </article>
        <article class="stock-detail-summary-card total">
            <span>Total Nilai Transaksi</span>
            <strong>Rp {{ number_format($summary['turnover_value'], 0, ',', '.') }}</strong>
        </article>
    </div>

    <div class="stock-detail-section-header">
        <div>
            <h3>Riwayat Stok</h3>
            <p>Urutan terbaru berada di atas.</p>
        </div>
    </div>

    <div class="stock-history-list">
        @forelse($history as $item)
            @php
                $isIncome = $item['type'] === 'income';
                $sign = $isIncome ? '-' : '+';
                $tone = $isIncome ? 'out' : 'in';
                $label = $isIncome ? 'Penjualan / Keluar' : 'Restock / Masuk';
            @endphp
            <article class="stock-history-item {{ $tone }}">
                <div class="stock-history-left">
                    <span class="stock-history-flag {{ $tone }}">{{ $label }}</span>
                    <h4>{{ $item['category'] }}</h4>
                    <p>{{ $item['date'] }}{{ $item['notes'] ? ' • '.$item['notes'] : '' }}</p>
                </div>
                <div class="stock-history-right">
                    @if($item['quantity'])
                        <div class="stock-history-meta">
                            {{ $sign }}{{ $item['quantity'] }} unit
                        </div>
                    @endif
                    <strong class="stock-history-amount {{ $tone }}">{{ $sign }} Rp {{ number_format($item['amount'], 0, ',', '.') }}</strong>
                    @if($item['unit_price'])
                        <small>{{ number_format($item['quantity'] ?? 0, 0, ',', '.') }} x Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</small>
                    @endif
                </div>
            </article>
        @empty
            <div class="stock-history-empty">
                <h4>Belum ada riwayat transaksi</h4>
                <p>Setelah produk ini dipakai di transaksi, riwayat stok akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.stock-detail-page {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.stock-back-link {
    display: inline-flex;
    width: fit-content;
    align-items: center;
    gap: 8px;
    color: #0e5c2e;
    text-decoration: none;
    font-weight: 700;
}

.stock-detail-hero {
    display: grid;
    grid-template-columns: 220px minmax(0, 1fr);
    gap: 20px;
    padding: 22px;
    border-radius: 28px;
    background: #fff;
    border: 1px solid #e8eaee;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
}

.stock-detail-hero.critical {
    border-color: rgba(220, 38, 38, 0.2);
}

.stock-detail-hero.warning {
    border-color: rgba(245, 158, 11, 0.2);
}

.stock-detail-image-wrap {
    border-radius: 24px;
    overflow: hidden;
    min-height: 220px;
    background: #f3f4f6;
}

.stock-detail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.stock-detail-main h2 {
    margin: 10px 0 8px;
    font-size: 32px;
    line-height: 1.1;
    color: #101828;
}

.stock-detail-copy {
    margin: 0 0 18px;
    color: #667085;
    max-width: 760px;
}

.stock-detail-topline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.stock-detail-badge,
.stock-history-flag {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
}

.stock-detail-badge.normal,
.stock-history-flag.in {
    background: #e6f7ec;
    color: #0e5c2e;
}

.stock-detail-badge.warning {
    background: #fff4db;
    color: #b45309;
}

.stock-detail-badge.critical,
.stock-history-flag.out {
    background: #fdeaea;
    color: #b42318;
}

.stock-detail-code {
    color: #667085;
    font-weight: 600;
}

.stock-detail-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.stock-detail-stats article,
.stock-detail-summary-card {
    padding: 14px 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.stock-detail-stats span,
.stock-detail-summary-card span {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6c7280;
}

.stock-detail-stats strong,
.stock-detail-summary-card strong {
    color: #101828;
    font-size: 18px;
}

.stock-detail-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.stock-detail-summary-card.success {
    background: linear-gradient(180deg, #f4fbf7 0%, #fff 100%);
    border-color: #b8dfca;
}

.stock-detail-summary-card.warning {
    background: linear-gradient(180deg, #fffaf0 0%, #fff 100%);
    border-color: #f3d58f;
}

.stock-detail-summary-card.total {
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
}

.stock-detail-section-header h3 {
    margin: 0;
    font-size: 22px;
}

.stock-detail-section-header p {
    margin: 4px 0 0;
    color: #667085;
}

.stock-history-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.stock-history-item {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: center;
    padding: 16px 18px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e8eaee;
    box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    border-left-width: 4px;
}

.stock-history-item.in {
    border-left-color: #0e5c2e;
}

.stock-history-item.out {
    border-left-color: #dc2626;
}

.stock-history-left h4 {
    margin: 10px 0 6px;
    font-size: 17px;
    color: #101828;
}

.stock-history-left p {
    margin: 0;
    color: #667085;
    font-size: 13px;
}

.stock-history-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: flex-end;
}

.stock-history-meta {
    font-size: 12px;
    font-weight: 700;
    color: #4b5563;
}

.stock-history-amount {
    font-size: 16px;
    font-weight: 800;
}

.stock-history-amount.in {
    color: #0e5c2e;
}

.stock-history-amount.out {
    color: #dc2626;
}

.stock-history-right small {
    color: #6c7280;
    font-weight: 600;
}

.stock-history-empty {
    padding: 28px;
    border-radius: 20px;
    border: 1px dashed #d7dde6;
    background: #fff;
    text-align: center;
}

.stock-history-empty h4 {
    margin: 0 0 8px;
    font-size: 18px;
}

.stock-history-empty p {
    margin: 0;
    color: #667085;
}

@media (max-width: 1024px) {
    .stock-detail-hero,
    .stock-detail-stats,
    .stock-detail-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .stock-detail-hero,
    .stock-detail-stats,
    .stock-detail-summary-grid {
        grid-template-columns: 1fr;
    }

    .stock-history-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .stock-history-right {
        align-items: flex-start;
        text-align: left;
    }
}
</style>
@endpush
