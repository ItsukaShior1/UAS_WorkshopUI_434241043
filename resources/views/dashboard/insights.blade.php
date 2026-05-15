@extends('layouts.dashboard')

@section('page-title', 'Insight')

@section('dashboard-content')
<!-- Insights Content -->
<div id="insightsContent" class="page-content active">
    <div class="page-header">
        <h2>Insight AI</h2>
    </div>

    <!-- Tren Terbaru Section -->
    <div class="insights-section">
        <h3 class="section-title">Tren Terbaru</h3>
        <div class="trends-list">
            @foreach($trends as $trend)
            <div class="trend-card">
                <span class="trend-icon">{{ $trend['icon'] }}</span>
                <div class="trend-content">
                    <h4>{{ $trend['title'] }}</h4>
                    <p>{{ $trend['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Performa Produk Section -->
    <div class="insights-section">
        <h3 class="section-title">Performa Produk</h3>
        <div class="product-performance">
            <div class="performance-item">
                <span class="rank">1</span>
                <div class="performance-info">
                    <h4>Product A</h4>
                    <p>Penjualan: 250 unit • Revenue: Rp 12.5M</p>
                </div>
                <span class="trend-badge up">↑ 35%</span>
            </div>
            <div class="performance-item">
                <span class="rank">2</span>
                <div class="performance-info">
                    <h4>Product B</h4>
                    <p>Penjualan: 180 unit • Revenue: Rp 13.5M</p>
                </div>
                <span class="trend-badge up">↑ 20%</span>
            </div>
            <div class="performance-item">
                <span class="rank">3</span>
                <div class="performance-info">
                    <h4>Product C</h4>
                    <p>Penjualan: 120 unit • Revenue: Rp 12M</p>
                </div>
                <span class="trend-badge down">↓ 5%</span>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Section -->
    <div class="insights-section">
        <h3 class="section-title">Rekomendasi</h3>
        <div class="recommendations-list">
            <div class="recommendation-card">
                <span class="rec-icon">💡</span>
                <p>Tingkatkan stok Product A karena permintaan tinggi dan trend naik</p>
            </div>
            <div class="recommendation-card">
                <span class="rec-icon">⚠️</span>
                <p>Evaluasi strategi marketing untuk Product C yang mengalami penurunan penjualan</p>
            </div>
            <div class="recommendation-card">
                <span class="rec-icon">📈</span>
                <p>Manfaatkan momentum penjualan bulan ini untuk ekspansi atau investasi</p>
            </div>
        </div>
    </div>

    <!-- Prediksi Pendapatan Section -->
    <div class="insights-section">
        <h3 class="section-title">Prediksi Pendapatan</h3>
        <div class="prediction-chart">
            <svg width="100%" height="180" viewBox="0 0 500 200">
                <!-- Simplified line chart for revenue prediction -->
                <polyline points="20,150 80,120 140,100 200,80 260,70 320,60 380,50 440,45" 
                          fill="none" stroke="#1b5e20" stroke-width="3"/>
                <circle cx="20" cy="150" r="4" fill="#1b5e20"/>
                <circle cx="80" cy="120" r="4" fill="#1b5e20"/>
                <circle cx="140" cy="100" r="4" fill="#1b5e20"/>
                <circle cx="200" cy="80" r="4" fill="#1b5e20"/>
                <circle cx="260" cy="70" r="4" fill="#1b5e20"/>
                <circle cx="320" cy="60" r="4" fill="#1b5e20"/>
                <circle cx="380" cy="50" r="4" fill="#1b5e20"/>
                <circle cx="440" cy="45" r="4" fill="#26c281"/>
                
                <!-- Grid lines -->
                <line x1="0" y1="180" x2="500" y2="180" stroke="#e0e0e0" stroke-width="1"/>
                <line x1="0" y1="150" x2="500" y2="150" stroke="#f5f5f5" stroke-width="1"/>
                <line x1="0" y1="120" x2="500" y2="120" stroke="#f5f5f5" stroke-width="1"/>
                
                <!-- Labels -->
                <text x="20" y="195" font-size="10" text-anchor="middle">Jun</text>
                <text x="80" y="195" font-size="10" text-anchor="middle">Jul</text>
                <text x="140" y="195" font-size="10" text-anchor="middle">Aug</text>
                <text x="200" y="195" font-size="10" text-anchor="middle">Sep</text>
                <text x="260" y="195" font-size="10" text-anchor="middle">Oct</text>
            </svg>
        </div>
        <p class="prediction-info">Prediksi pendapatan untuk 5 bulan ke depan</p>
    </div>
</div>
@endsection

@push('styles')
<style>
.insights-section {
    margin-bottom: 24px;
}
.section-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
}
.trends-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.trend-card {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
.trend-icon {
    font-size: 24px;
    flex-shrink: 0;
}
.trend-content h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
}
.trend-content p {
    margin: 0;
    font-size: 12px;
    color: #666;
}
.product-performance {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.performance-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
.rank {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1b5e20;
    color: white;
    border-radius: 50%;
    font-weight: 700;
}
.performance-info {
    flex: 1;
}
.performance-info h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
}
.performance-info p {
    margin: 0;
    font-size: 12px;
    color: #666;
}
.trend-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.trend-badge.up {
    background: #e0f5e0;
    color: #26c281;
}
.trend-badge.down {
    background: #ffe0e0;
    color: #e74c3c;
}
.recommendations-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.recommendation-card {
    display: flex;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border-left: 4px solid #26c281;
}
.rec-icon {
    font-size: 18px;
}
.recommendation-card p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
}
.prediction-chart {
    background: white;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 8px;
}
.prediction-info {
    text-align: center;
    font-size: 12px;
    color: #999;
    margin: 0;
}

@media (max-width: 768px) {
    .performance-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endpush
