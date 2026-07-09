@extends('layouts.dashboard')

@section('page-title', 'Insight')

@section('dashboard-content')
<div id="insightsContent" class="page-content active insights-page">
    <header class="insights-hero">
        <div>
            <p class="hero-label">Analitik Bisnis</p>
            <h2>Insight AI</h2>
            <p class="hero-subtitle">Ringkasan tren, performa produk, dan rekomendasi untuk membantu keputusan bisnis harian.</p>
        </div>
        <div class="hero-meta">
            <i data-lucide="calendar-clock"></i>
            <span>Diperbarui {{ now()->translatedFormat('d M Y') }}</span>
        </div>
    </header>

    <section class="insights-panel">
        <div class="panel-title-wrap">
            <i data-lucide="sparkles"></i>
            <h3 class="section-title">Tren Terbaru</h3>
        </div>
        <div class="trends-list">
            @php
                $trendIcons = ['trending-up', 'boxes', 'wallet'];
            @endphp
            @foreach($trends as $index => $trend)
            <article class="trend-card">
                <span class="trend-icon-wrap">
                    <i data-lucide="{{ $trendIcons[$index] ?? 'line-chart' }}"></i>
                </span>
                <div class="trend-content">
                    <h4>{{ $trend['title'] }}</h4>
                    <p>{{ $trend['description'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </section>

    <section class="insights-panel">
        <div class="panel-title-wrap">
            <i data-lucide="bar-chart-3"></i>
            <h3 class="section-title">Performa Produk</h3>
        </div>
        <div class="product-performance">
            <article class="performance-item">
                <span class="rank">1</span>
                <div class="performance-info">
                    <h4>Product A</h4>
                    <p>Penjualan: 250 unit • Revenue: Rp 12.5M</p>
                    <div class="performance-bar"><span style="width: 84%"></span></div>
                </div>
                <span class="trend-badge up">Naik 35%</span>
            </article>
            <article class="performance-item">
                <span class="rank">2</span>
                <div class="performance-info">
                    <h4>Product B</h4>
                    <p>Penjualan: 180 unit • Revenue: Rp 13.5M</p>
                    <div class="performance-bar"><span style="width: 68%"></span></div>
                </div>
                <span class="trend-badge up">Naik 20%</span>
            </article>
            <article class="performance-item">
                <span class="rank">3</span>
                <div class="performance-info">
                    <h4>Product C</h4>
                    <p>Penjualan: 120 unit • Revenue: Rp 12M</p>
                    <div class="performance-bar"><span class="down" style="width: 44%"></span></div>
                </div>
                <span class="trend-badge down">Turun 5%</span>
            </article>
        </div>
    </section>

    <section class="insights-panel">
        <div class="panel-title-wrap">
            <i data-lucide="lightbulb"></i>
            <h3 class="section-title">Rekomendasi</h3>
        </div>
        <div class="recommendations-list">
            <article class="recommendation-card">
                <span class="rec-icon-wrap success"><i data-lucide="target"></i></span>
                <p>Tingkatkan stok Product A karena permintaan tinggi dan tren sedang naik stabil.</p>
            </article>
            <article class="recommendation-card">
                <span class="rec-icon-wrap warning"><i data-lucide="triangle-alert"></i></span>
                <p>Evaluasi strategi pemasaran Product C karena performa penjualan menunjukkan penurunan.</p>
            </article>
            <article class="recommendation-card">
                <span class="rec-icon-wrap info"><i data-lucide="rocket"></i></span>
                <p>Manfaatkan momentum bulan ini untuk ekspansi katalog atau uji produk baru.</p>
            </article>
        </div>
    </section>

    <section class="insights-panel">
        <div class="panel-title-wrap">
            <i data-lucide="activity"></i>
            <h3 class="section-title">Prediksi Pendapatan</h3>
        </div>
        <div class="prediction-chart-wrap">
            <svg class="prediction-chart" viewBox="0 0 500 220" aria-label="Grafik prediksi pendapatan">
                <defs>
                    <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#0f9f57" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="#0f9f57" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <line x1="20" y1="180" x2="480" y2="180" stroke="#dbe3ea" stroke-width="1"/>
                <line x1="20" y1="140" x2="480" y2="140" stroke="#edf2f7" stroke-width="1"/>
                <line x1="20" y1="100" x2="480" y2="100" stroke="#edf2f7" stroke-width="1"/>

                <path d="M20 160 L80 145 L140 126 L200 108 L260 94 L320 82 L380 70 L440 58 L440 180 L20 180 Z" fill="url(#lineGradient)"/>
                <polyline points="20,160 80,145 140,126 200,108 260,94 320,82 380,70 440,58" fill="none" stroke="#0f9f57" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>

                <circle cx="20" cy="160" r="4" fill="#0f9f57"/>
                <circle cx="80" cy="145" r="4" fill="#0f9f57"/>
                <circle cx="140" cy="126" r="4" fill="#0f9f57"/>
                <circle cx="200" cy="108" r="4" fill="#0f9f57"/>
                <circle cx="260" cy="94" r="4" fill="#0f9f57"/>
                <circle cx="320" cy="82" r="4" fill="#0f9f57"/>
                <circle cx="380" cy="70" r="4" fill="#0f9f57"/>
                <circle cx="440" cy="58" r="5" fill="#0f5a34"/>

                <text x="20" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Jun</text>
                <text x="80" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Jul</text>
                <text x="140" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Agu</text>
                <text x="200" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Sep</text>
                <text x="260" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Okt</text>
                <text x="320" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Nov</text>
                <text x="380" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Des</text>
                <text x="440" y="202" font-size="11" text-anchor="middle" fill="#6b7280">Jan</text>
            </svg>
        </div>
        <p class="prediction-info">Prediksi pendapatan 8 bulan ke depan menunjukkan tren kenaikan bertahap.</p>
    </section>
</div>
@endsection

@push('styles')
<style>
:root {
    --ins-bg: #f7fafc;
    --ins-surface: #ffffff;
    --ins-border: #e2e8f0;
    --ins-text: #0f172a;
    --ins-muted: #64748b;
    --ins-primary: #0f5a34;
    --ins-primary-soft: #e7f6ee;
    --ins-danger-soft: #fef2f2;
    --ins-danger: #dc2626;
}

.insights-page {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.insights-hero,
.insights-panel {
    background: var(--ins-surface);
    border: 1px solid var(--ins-border);
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
}

.insights-hero {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 22px;
    align-items: flex-start;
}

.hero-label {
    margin: 0 0 6px;
    color: var(--ins-primary);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 700;
}

.insights-hero h2 {
    margin: 0;
    color: var(--ins-text);
    font-size: 28px;
    line-height: 1.2;
}

.hero-subtitle {
    margin: 10px 0 0;
    color: var(--ins-muted);
    max-width: 700px;
    line-height: 1.6;
    font-size: 14px;
}

.hero-meta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--ins-muted);
    background: #f8fafc;
    border: 1px solid var(--ins-border);
    border-radius: 999px;
    padding: 8px 12px;
    white-space: nowrap;
}

.hero-meta i {
    width: 14px;
    height: 14px;
}

.insights-panel {
    padding: 18px;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}

.panel-title-wrap i {
    width: 18px;
    height: 18px;
    color: var(--ins-primary);
}

.section-title {
    margin: 0;
    font-size: 17px;
    color: var(--ins-text);
    font-weight: 700;
}

.trends-list,
.product-performance,
.recommendations-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.trend-card,
.performance-item,
.recommendation-card {
    background: #fff;
    border: 1px solid var(--ins-border);
    border-radius: 14px;
    padding: 14px;
}

.trend-card {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.trend-icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--ins-primary-soft);
    color: var(--ins-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.trend-icon-wrap i {
    width: 18px;
    height: 18px;
}

.trend-content h4 {
    margin: 0 0 4px;
    font-size: 16px;
    color: var(--ins-text);
}

.trend-content p {
    margin: 0;
    color: var(--ins-muted);
    font-size: 13px;
    line-height: 1.5;
}

.performance-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rank {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    background: var(--ins-primary);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.performance-info {
    flex: 1;
    min-width: 0;
}

.performance-info h4 {
    margin: 0 0 4px;
    color: var(--ins-text);
    font-size: 15px;
}

.performance-info p {
    margin: 0;
    color: var(--ins-muted);
    font-size: 13px;
}

.performance-bar {
    margin-top: 8px;
    width: 100%;
    height: 8px;
    border-radius: 999px;
    background: #ecf1f6;
    overflow: hidden;
}

.performance-bar span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, #0f9f57, #17b26a);
}

.performance-bar span.down {
    background: linear-gradient(90deg, #ef4444, #f97316);
}

.trend-badge {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.trend-badge.up {
    background: #e7f8ee;
    color: #0f9f57;
}

.trend-badge.down {
    background: var(--ins-danger-soft);
    color: var(--ins-danger);
}

.recommendation-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.rec-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rec-icon-wrap i {
    width: 16px;
    height: 16px;
}

.rec-icon-wrap.success {
    background: #e7f8ee;
    color: #0f9f57;
}

.rec-icon-wrap.warning {
    background: #fff7e8;
    color: #b7791f;
}

.rec-icon-wrap.info {
    background: #ecf3ff;
    color: #2563eb;
}

.recommendation-card p {
    margin: 0;
    color: #334155;
    line-height: 1.6;
    font-size: 14px;
}

.prediction-chart-wrap {
    background: #fbfdff;
    border: 1px solid var(--ins-border);
    border-radius: 14px;
    padding: 12px;
}

.prediction-chart {
    width: 100%;
    height: auto;
    display: block;
}

.prediction-info {
    margin: 10px 0 0;
    color: var(--ins-muted);
    font-size: 13px;
}

@media (max-width: 900px) {
    .insights-hero {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .insights-page {
        gap: 14px;
    }

    .insights-hero h2 {
        font-size: 24px;
    }

    .performance-item {
        flex-wrap: wrap;
    }

    .trend-badge {
        margin-left: 46px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
});
</script>
@endpush
