@extends('layouts.dashboard')

@section('page-title', 'Transaksi')

@section('dashboard-content')
<!-- Transactions Content -->
<div id="transactionsContent" class="page-content active">
    <div class="page-header" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0;">Catat Transaksi</h2>
        <button class="btn btn-primary" style="padding: 10px 20px; background-color: #074F2A; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;" onclick="alert('Fitur tambah transaksi sedang dalam pengembangan')">+ Transaksi Baru</button>
    </div>

    <div class="transaction-filters">
        <div class="filter-group">
            <label>Tipe</label>
            <select style="padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; background-color: white; cursor: pointer;">
                <option>Semua</option>
                <option>Pemasukan</option>
                <option>Pengeluaran</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Bulan</label>
            <select style="padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; background-color: white; cursor: pointer;">
                <option selected>Mei 2024</option>
                <option>April 2024</option>
                <option>Maret 2024</option>
            </select>
        </div>
    </div>

    <div class="transactions-list">
        @foreach($transactions as $transaction)
        <div class="transaction-item">
            <div class="transaction-icon" style="background-color: {{ $transaction['type'] == 'income' ? '#E8F5E9' : '#FFEBEE' }}; color: {{ $transaction['type'] == 'income' ? '#10B981' : '#DC2626' }};">
                @if($transaction['type'] == 'income')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12l7-7 7 7"/>
                    </svg>
                @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 19V5M5 12l7 7 7-7"/>
                    </svg>
                @endif
            </div>
            <div class="transaction-details">
                <h4 style="margin: 0 0 4px 0; color: #1A1A1A; font-size: 16px;">{{ $transaction['name'] }}</h4>
                <p style="margin: 0; font-size: 12px; color: #6B7280;">{{ $transaction['category'] }} • {{ $transaction['date'] }}</p>
            </div>
            <div class="transaction-amount">
                <span class="{{ $transaction['type'] == 'income' ? 'income' : 'expense' }}" style="font-size: 16px; font-weight: 700;">
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
    background: white;
    padding: 16px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #E5E7EB;
}

.transaction-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.transaction-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    flex-shrink: 0;
}

.transaction-details {
    flex: 1;
}

.transaction-amount {
    text-align: right;
    font-weight: 700;
}

.transaction-amount.income {
    color: #10B981;
}

.transaction-amount.expense {
    color: #DC2626;
}
</style>
@endpush
