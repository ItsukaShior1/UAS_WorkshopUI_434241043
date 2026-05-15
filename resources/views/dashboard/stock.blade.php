@extends('layouts.dashboard')

@section('page-title', 'Stok')

@section('dashboard-content')
<!-- Stock Content -->
<div id="stockContent" class="page-content active">
    <div class="page-header">
        <h2>Kelola Stok</h2>
        <button class="btn btn-primary" onclick="alert('Fitur tambah produk sedang dalam pengembangan')">+ Produk Baru</button>
    </div>

    <div class="stock-list">
        @foreach($products as $product)
        <div class="stock-item">
            <div class="stock-info">
                <h4>{{ $product['name'] }}</h4>
                <div class="stock-details">
                    @if($product['status'] == 'critical')
                        <span class="status-badge critical">🔴 Stok Kritis ({{ $product['stock'] }} unit)</span>
                    @elseif($product['status'] == 'warning')
                        <span class="status-badge warning">🟡 Stok Rendah ({{ $product['stock'] }} unit)</span>
                    @else
                        <span class="status-badge normal">🟢 Stok Normal ({{ $product['stock'] }} unit)</span>
                    @endif
                    <span class="price">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="stock-actions">
                <button class="btn-icon" onclick="alert('Edit sedang dalam pengembangan')">✏️</button>
                <button class="btn-icon danger" onclick="alert('Delete sedang dalam pengembangan')">🗑️</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
.stock-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: white;
    border-radius: 8px;
    margin-bottom: 12px;
    border: 1px solid #e0e0e0;
}
.stock-info {
    flex: 1;
}
.stock-info h4 {
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 600;
}
.stock-details {
    display: flex;
    gap: 12px;
    font-size: 12px;
}
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    background: #f5f5f5;
}
.status-badge.critical {
    background: #ffe0e0;
    color: #c33;
}
.status-badge.warning {
    background: #fff8e0;
    color: #f39c12;
}
.status-badge.normal {
    background: #e0f5e0;
    color: #26c281;
}
.stock-actions {
    display: flex;
    gap: 8px;
}
.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    padding: 6px 10px;
}
.btn-icon.danger {
    color: #e74c3c;
}
</style>
@endpush
