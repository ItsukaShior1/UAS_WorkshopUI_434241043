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
        <h3>📊 Pemasukan vs Pengeluaran</h3>
        <div class="bar-chart">
            <svg width="100%" height="280" viewBox="0 0 600 280" style="max-width: 100%; height: auto;">
                <!-- Grid lines -->
                <line x1="50" y1="200" x2="550" y2="200" stroke="#E5E7EB" stroke-width="1"/>
                <line x1="50" y1="150" x2="550" y2="150" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="2,2"/>
                <line x1="50" y1="100" x2="550" y2="100" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="2,2"/>
                <line x1="50" y1="50" x2="550" y2="50" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="2,2"/>
                
                <!-- Y-axis -->
                <line x1="50" y1="30" x2="50" y2="210" stroke="#1A1A1A" stroke-width="2"/>
                <!-- X-axis -->
                <line x1="50" y1="210" x2="550" y2="210" stroke="#1A1A1A" stroke-width="2"/>
                
                <!-- Data for January to May -->
                <!-- January (1.5M / 800K) -->
                <rect x="70" y="115" width="20" height="95" fill="#49D18D" rx="2"/>
                <rect x="95" y="145" width="20" height="65" fill="#DC2626" rx="2"/>
                <text x="90" y="230" font-size="12" font-weight="600" text-anchor="middle" fill="#1A1A1A">Jan</text>
                
                <!-- February (1.8M / 900K) -->
                <rect x="115" y="95" width="20" height="115" fill="#49D18D" rx="2"/>
                <rect x="140" y="135" width="20" height="75" fill="#DC2626" rx="2"/>
                <text x="135" y="230" font-size="12" font-weight="600" text-anchor="middle" fill="#1A1A1A">Feb</text>
                
                <!-- March (2M / 1M) -->
                <rect x="160" y="80" width="20" height="130" fill="#49D18D" rx="2"/>
                <rect x="185" y="127" width="20" height="83" fill="#DC2626" rx="2"/>
                <text x="180" y="230" font-size="12" font-weight="600" text-anchor="middle" fill="#1A1A1A">Mar</text>
                
                <!-- April (1.7M / 900K) -->
                <rect x="205" y="105" width="20" height="105" fill="#49D18D" rx="2"/>
                <rect x="230" y="137" width="20" height="73" fill="#DC2626" rx="2"/>
                <text x="225" y="230" font-size="12" font-weight="600" text-anchor="middle" fill="#1A1A1A">Apr</text>
                
                <!-- May (2.2M / 850K) -->
                <rect x="250" y="70" width="20" height="140" fill="#49D18D" rx="2"/>
                <rect x="275" y="142" width="20" height="68" fill="#DC2626" rx="2"/>
                <text x="270" y="230" font-size="12" font-weight="600" text-anchor="middle" fill="#1A1A1A">Mei</text>
            </svg>
        </div>
        <div class="chart-legend">
            <span style="color: #49D18D; font-weight: 600;">■ Pemasukan</span>
            <span style="color: #DC2626; font-weight: 600;">■ Pengeluaran</span>
        </div>
    </div>

    <!-- Distribusi Pengeluaran Pie Chart -->
    <div class="chart-card">
        <h3>🥧 Distribusi Pengeluaran</h3>
        <div class="pie-chart">
            <div class="pie-container">
                <svg width="250" height="250" viewBox="0 0 250 250" style="max-width: 100%;">
                    <!-- Bahan Baku 40% (Dark Green) - 0° to 144° -->
                    <path d="M 125 25 
                             A 100 100 0 0 1 183.78 205.88 
                             L 125 125 
                             Z" 
                          fill="#074F2A" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                    
                    <!-- Gaji 25% (Bright Green) - 144° to 234° -->
                    <path d="M 183.78 205.88 
                             A 100 100 0 0 1 44.1 183.78 
                             L 125 125 
                             Z" 
                          fill="#49D18D" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                    
                    <!-- Utilitas 10% (Orange) - 234° to 270° -->
                    <path d="M 44.1 183.78 
                             A 100 100 0 0 1 25 125 
                             L 125 125 
                             Z" 
                          fill="#F59E0B" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                    
                    <!-- Lainnya 25% (Red) - 270° to 360° -->
                    <path d="M 25 125 
                             A 100 100 0 0 1 125 25 
                             L 125 125 
                             Z" 
                          fill="#DC2626" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="pie-legend">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #074F2A;"></span>
                    <span class="legend-label">Bahan Baku <strong>40%</strong></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #49D18D;"></span>
                    <span class="legend-label">Gaji <strong>25%</strong></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #F59E0B;"></span>
                    <span class="legend-label">Utilitas <strong>10%</strong></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #DC2626;"></span>
                    <span class="legend-label">Lainnya <strong>25%</strong></span>
                </div>
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
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 18px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}
.ringkasan-header h3 {
    margin: 0 0 14px 0;
    font-size: 15px;
}
.ringkasan-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}
.ringkasan-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.ringkasan-label {
    font-size: 11px;
    color: #666;
}
.ringkasan-value {
    font-size: 16px;
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
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 18px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}
.chart-card h3 {
    margin: 0 0 14px 0;
    font-size: 15px;
}
.bar-chart {
    margin-bottom: 10px;
    overflow-x: auto;
}

.bar-chart svg {
    display: block;
    margin: 0 auto;
    max-width: 100%;
}

.chart-legend {
    display: flex;
    gap: 20px;
    font-size: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.pie-chart {
    display: flex;
    gap: 24px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    padding: 10px 0 4px;
}

.pie-container {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pie-chart svg {
    width: 180px;
    height: 180px;
}

.pie-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
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
    border-radius: 3px;
    flex-shrink: 0;
}

.legend-label {
    color: #1A1A1A;
    font-weight: 500;
}

.legend-label strong {
    color: #074F2A;
    font-weight: 700;
}
.report-actions {
    margin-top: 18px;
    display: flex;
    justify-content: flex-end;
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
