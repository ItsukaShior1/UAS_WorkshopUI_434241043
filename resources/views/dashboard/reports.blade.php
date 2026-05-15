@extends('layouts.dashboard')

@section('page-title', 'Laporan')

@section('dashboard-content')
<!-- Reports Content -->
<div id="reportsContent" class="page-content active">
    <div class="page-header">
        <h2>Laporan Keuangan</h2>
        <select id="reportMonth">
            <option selected>Mei 2024</option>
            <option>April 2024</option>
            <option>Maret 2024</option>
            <option>Februari 2024</option>
            <option>Januari 2024</option>
        </select>
    </div>

    <!-- Ringkasan Bulan Ini -->
    <div class="ringkasan-card">
        <div class="ringkasan-header">
            <h3>Ringkasan Bulan Ini</h3>
        </div>
        <div class="ringkasan-content">
            <div class="ringkasan-item">
                <span class="ringkasan-label">Pemasukan</span>
                <span class="ringkasan-value income">Rp {{ number_format($summary['income'], 0, ',', '.') }}</span>
            </div>
            <div class="ringkasan-item">
                <span class="ringkasan-label">Pengeluaran</span>
                <span class="ringkasan-value expense">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</span>
            </div>
            <div class="ringkasan-item">
                <span class="ringkasan-label">Saldo</span>
                <span class="ringkasan-value balance">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Pemasukan vs Pengeluaran Chart -->
    <div class="chart-card">
        <h3>Pemasukan vs Pengeluaran</h3>
        <div class="bar-chart">
            <svg width="100%" height="200" viewBox="0 0 500 200">
                <!-- Simplified bar chart representation -->
                @foreach($chartData as $index => $data)
                    @php
                        $x = 50 + ($index * 85);
                        $incomeHeight = ($data['income'] / 2250000) * 100;
                        $expenseHeight = ($data['expense'] / 900000) * 100;
                    @endphp
                    <!-- Income bar -->
                    <rect x="{{ $x }}" y="{{ 150 - $incomeHeight }}" width="15" height="{{ $incomeHeight }}" fill="#26c281"/>
                    <!-- Expense bar -->
                    <rect x="{{ $x + 20 }}" y="{{ 150 - $expenseHeight }}" width="15" height="{{ $expenseHeight }}" fill="#e74c3c"/>
                    <!-- Label -->
                    <text x="{{ $x + 10 }}" y="170" font-size="10" text-anchor="middle">{{ substr($data['month'], 0, 3) }}</text>
                @endforeach
            </svg>
        </div>
        <div class="chart-legend">
            <span style="color: #26c281;">■ Pemasukan</span>
            <span style="color: #e74c3c;">■ Pengeluaran</span>
        </div>
    </div>

    <!-- Distribusi Pengeluaran Pie Chart -->
    <div class="chart-card">
        <h3>Distribusi Pengeluaran</h3>
        <div class="pie-chart">
            <svg width="100%" height="220" viewBox="0 0 400 250">
                <!-- Pie chart segments (simplified) -->
                <!-- Bahan Baku 40% (Dark Green) -->
                <path d="M 200 100 L 280 100 A 80 80 0 0 1 156.57 36.57 Z" fill="#1b5e20" stroke="white" stroke-width="2"/>
                <!-- Gaji 25% (Light Green) -->
                <path d="M 200 100 L 156.57 36.57 A 80 80 0 0 1 40 100 Z" fill="#26c281" stroke="white" stroke-width="2"/>
                <!-- Utilitas 10% (Orange) -->
                <path d="M 200 100 L 40 100 A 80 80 0 0 1 40 100 Z" fill="#f39c12" stroke="white" stroke-width="2"/>
                <!-- Lainnya 25% (Red) -->
                <path d="M 200 100 L 40 100 A 80 80 0 0 1 280 100 Z" fill="#e74c3c" stroke="white" stroke-width="2"/>
            </svg>
            <div class="pie-legend">
                @foreach($expenseDistribution as $expense)
                <div class="legend-item">
                    <span class="legend-color" style="background-color: {{ $loop->index == 0 ? '#1b5e20' : ($loop->index == 1 ? '#26c281' : ($loop->index == 2 ? '#f39c12' : '#e74c3c')) }};"></span>
                    <span class="legend-label">{{ $expense['name'] }} {{ $expense['percentage'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Download PDF Button -->
    <div class="report-actions">
        <button class="btn btn-outline" onclick="alert('Download PDF sedang dalam pengembangan')">
            📥 Download PDF
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
.ringkasan-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}
.ringkasan-header h3 {
    margin: 0 0 16px 0;
}
.ringkasan-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}
.ringkasan-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ringkasan-label {
    font-size: 12px;
    color: #666;
}
.ringkasan-value {
    font-size: 18px;
    font-weight: 700;
}
.ringkasan-value.income {
    color: #26c281;
}
.ringkasan-value.expense {
    color: #e74c3c;
}
.ringkasan-value.balance {
    color: #1b5e20;
}
.chart-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}
.chart-card h3 {
    margin: 0 0 16px 0;
}
.bar-chart {
    margin-bottom: 12px;
}
.chart-legend {
    display: flex;
    gap: 20px;
    font-size: 12px;
}
.pie-chart {
    display: flex;
    gap: 20px;
    align-items: center;
}
.pie-legend {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 150px;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}
.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}
.report-actions {
    margin-top: 20px;
}

@media (max-width: 768px) {
    .ringkasan-content {
        grid-template-columns: 1fr;
    }
    .pie-chart {
        flex-direction: column;
    }
}
</style>
@endpush
