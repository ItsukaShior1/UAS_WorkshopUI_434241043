@extends('layouts.dashboard')

@section('page-title', 'Home')

@section('dashboard-content')
<!-- Home Content -->
<div id="homeContent" class="page-content active">
    <!-- Balance Card -->
    <div class="balance-section">
        <div class="balance-header">
            <h3>Saldo Kas</h3>
            <span class="month-label">Mei 2024</span>
        </div>
        <div class="balance-display">
            <h1>Rp {{ number_format($balance, 0, ',', '.') }}</h1>
            <div class="balance-details">
                <div class="balance-item">
                    <span class="amount">Rp {{ number_format($income, 0, ',', '.') }}</span>
                    <span class="label">Pemasukan</span>
                </div>
                <div class="balance-item">
                    <span class="amount">Rp {{ number_format($expense, 0, ',', '.') }}</span>
                    <span class="label">Pengeluaran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="chart-section">
        <h3>Tren Bulanan</h3>
        <div class="chart-placeholder">
            <svg width="100%" height="150" viewBox="0 0 400 150">
                <polyline points="10,120 60,80 110,100 160,60 210,70 260,40 310,50 360,30" 
                          fill="none" stroke="#26c281" stroke-width="2"/>
            </svg>
        </div>
        <p class="trend-info">+{{ $trend }}% dibanding bulan lalu</p>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Akses Cepat</h3>
        <div class="actions-grid">
            <a href="{{ route('dashboard.transactions') }}" class="action-btn">
                <span class="icon">💳</span>
                <span class="label">Catat Transaksi</span>
            </a>
            <a href="{{ route('dashboard.stock') }}" class="action-btn">
                <span class="icon">📦</span>
                <span class="label">Kelola Stok</span>
            </a>
            <a href="{{ route('dashboard.reports') }}" class="action-btn">
                <span class="icon">📊</span>
                <span class="label">Laporan Keuangan</span>
            </a>
            <a href="{{ route('dashboard.insights') }}" class="action-btn">
                <span class="icon">✨</span>
                <span class="label">Insight AI</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <div class="alerts-section">
        <div class="alert alert-warning">
            <span class="alert-icon">⚠️</span>
            <div class="alert-content">
                <h4>Stok Hampir Habis</h4>
                <p>2 produk perlu diperhatikan</p>
            </div>
            <a href="{{ route('dashboard.stock') }}" class="alert-link">Lihat Sekarang</a>
        </div>
    </div>
</div>
@endsection
