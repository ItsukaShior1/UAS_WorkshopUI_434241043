@extends('layouts.dashboard')

@section('page-title', 'Transaksi')

@section('dashboard-content')
<!-- Transactions Content -->
<div id="transactionsContent" class="page-content active">
    <div class="page-header">
        <h2>Catat Transaksi</h2>
        <button class="btn btn-primary" onclick="alert('Fitur tambah transaksi sedang dalam pengembangan')">+ Transaksi Baru</button>
    </div>

    <div class="transaction-filters">
        <div class="filter-group">
            <label>Tipe</label>
            <select>
                <option>Semua</option>
                <option>Pemasukan</option>
                <option>Pengeluaran</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Bulan</label>
            <select>
                <option selected>Mei 2024</option>
                <option>April 2024</option>
                <option>Maret 2024</option>
            </select>
        </div>
    </div>

    <div class="transactions-list">
        @foreach($transactions as $transaction)
        <div class="transaction-item">
            <div class="transaction-icon">
                @if($transaction['type'] == 'income')
                    <span style="font-size: 20px;">📈</span>
                @else
                    <span style="font-size: 20px;">📉</span>
                @endif
            </div>
            <div class="transaction-info">
                <h4>{{ $transaction['name'] }}</h4>
                <p style="font-size: 12px; color: #999;">{{ $transaction['category'] }} • {{ $transaction['date'] }}</p>
            </div>
            <div class="transaction-amount">
                <span class="{{ $transaction['type'] == 'income' ? 'income' : 'expense' }}">
                    {{ $transaction['type'] == 'income' ? '+' : '-' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
.transaction-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: white;
    border-radius: 8px;
    margin-bottom: 8px;
    border: 1px solid #e0e0e0;
}
.transaction-icon {
    margin-right: 12px;
}
.transaction-info {
    flex: 1;
}
.transaction-amount {
    text-align: right;
    font-weight: 600;
}
.transaction-amount .income {
    color: #26c281;
}
.transaction-amount .expense {
    color: #e74c3c;
}
</style>
@endpush
