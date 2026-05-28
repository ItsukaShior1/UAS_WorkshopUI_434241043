@extends('layouts.dashboard')

@section('page-title', 'Home')

@section('dashboard-content')
<!-- Home Content -->
<div id="homeContent" class="page-content active">
    <div class="home-overview-grid">
        <!-- Balance Card -->
        <div class="balance-section dashboard-panel dashboard-panel-hero">
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

            <div class="recent-transactions-panel">
                <div class="recent-transactions-header">
                    <h4>Recent Transactions</h4>
                    <span>Terbaru</span>
                </div>

                <div class="recent-transactions-list">
                    @foreach(array_slice($transactions, 0, 4) as $transaction)
                        <div class="recent-transaction-item">
                            <span class="recent-transaction-bubble {{ $transaction['type'] == 'income' ? 'income' : 'expense' }}"></span>
                            <div class="recent-transaction-content">
                                <div class="recent-transaction-name">{{ $transaction['name'] }}</div>
                                <div class="recent-transaction-meta">
                                    {{ $transaction['category'] }} • {{ $transaction['date'] }}
                                </div>
                            </div>
                            <div class="recent-transaction-amount {{ $transaction['type'] == 'income' ? 'income' : 'expense' }}">
                                {{ $transaction['type'] == 'income' ? '+' : '-' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="home-secondary-grid">
            <!-- Trend Chart -->
            <div class="chart-section dashboard-panel">
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
            <div class="quick-actions dashboard-panel">
                <h3>Akses Cepat</h3>
                <div class="actions-grid">
                    <a href="{{ route('dashboard.transactions') }}" class="action-btn">
                        <img src="{{ asset('img/icons/transactions.png') }}" alt="Catat Transaksi" class="action-icon-img">
                        <span class="label">Catat Transaksi</span>
                    </a>
                    <a href="{{ route('dashboard.stock') }}" class="action-btn">
                        <img src="{{ asset('img/icons/stock.png') }}" alt="Kelola Stok" class="action-icon-img">
                        <span class="label">Kelola Stok</span>
                    </a>
                    <a href="{{ route('dashboard.reports') }}" class="action-btn">
                        <img src="{{ asset('img/icons/reports.png') }}" alt="Laporan Keuangan" class="action-icon-img">
                        <span class="label">Laporan Keuangan</span>
                    </a>
                    <a href="{{ route('dashboard.insights') }}" class="action-btn">
                        <img src="{{ asset('img/icons/insights.png') }}" alt="Insight AI" class="action-icon-img">
                        <span class="label">Insight AI</span>
                    </a>
                </div>
            </div>
        </div>

    <!-- Alerts -->
    <div class="alerts-section dashboard-panel">
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
