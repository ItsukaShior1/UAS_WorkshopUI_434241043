@extends('layouts.dashboard')

@section('page-title', 'Laporan')

@section('dashboard-content')
@php
    $chartSeries = collect($chartData);
    $maxIncome = max(1, (int) $chartSeries->max('income'));
    $maxExpense = max(1, (int) $chartSeries->max('expense'));
    $chartHeight = 152;
@endphp

<div class="reports-shell">
    <div class="reports-header">
        <div>
            <p class="reports-kicker">Dashboard laporan</p>
            <h2>Laporan Keuangan & Stok</h2>
            <p class="reports-description">Data di bawah ini diambil langsung dari transaksi dan stok yang sudah tersimpan, jadi perubahan barang, penjualan, dan restock ikut ter-update otomatis.</p>
        </div>
        <div class="reports-toolbar">
            <form method="GET" action="{{ route('dashboard.reports') }}" class="reports-filter">
                <label for="reportMonth">Periode</label>
                <select id="reportMonth" name="month" onchange="this.form.submit()">
                    @foreach($months as $month)
                        <option value="{{ $month['key'] }}" {{ $month['key'] === $reportMonthKey ? 'selected' : '' }}>{{ $month['label'] }}</option>
                    @endforeach
                </select>
            </form>
            <a class="reports-pdf" href="{{ route('dashboard.reports.pdf', ['month' => $reportMonthKey, 'download' => 1]) }}">Download PDF</a>
        </div>
    </div>

    <div class="metrics-grid">
        @foreach($highlightCards as $card)
            <article class="metric-card {{ $card['tone'] }}">
                <div class="metric-top">
                    <span>{{ $card['label'] }}</span>
                    <strong>{{ $card['trend'] }}</strong>
                </div>
                <div class="metric-value">{{ $card['value'] }}</div>
                <p>{{ $card['hint'] }}</p>
            </article>
        @endforeach
    </div>

    <div class="analytics-grid">
        <section class="panel panel-chart">
            <div class="panel-head">
                <div>
                    <h3>Total Profit Overview</h3>
                    <p>Pemasukan dan pengeluaran per bulan</p>
                </div>
                <div class="panel-summary">
                    <div>
                        <span>Total Revenue</span>
                        <strong>Rp {{ number_format($summary['income'], 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span>Saldo</span>
                        <strong>Rp {{ number_format($summary['balance'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="bar-chart-wrap">
                <svg viewBox="0 0 760 280" class="bar-chart-svg" preserveAspectRatio="none">
                    <defs>
                        <pattern id="gridDots" width="12" height="12" patternUnits="userSpaceOnUse">
                            <circle cx="1.5" cy="1.5" r="1.2" fill="#EEF2F7"></circle>
                        </pattern>
                    </defs>
                    <rect x="56" y="26" width="648" height="190" fill="url(#gridDots)" opacity="0.28"></rect>
                    <line x1="56" y1="218" x2="704" y2="218" stroke="#E5E7EB" stroke-width="1.5"></line>
                    <line x1="56" y1="170" x2="704" y2="170" stroke="#EEF2F7" stroke-width="1"></line>
                    <line x1="56" y1="122" x2="704" y2="122" stroke="#EEF2F7" stroke-width="1"></line>
                    <line x1="56" y1="74" x2="704" y2="74" stroke="#EEF2F7" stroke-width="1"></line>

                    @foreach($chartSeries as $index => $point)
                        @php
                            $slot = 620 / max(1, count($chartSeries));
                            $baseX = 78 + ($index * $slot);
                            $incomeHeight = (int) round(($point['income'] / $maxIncome) * $chartHeight);
                            $expenseHeight = (int) round(($point['expense'] / $maxExpense) * $chartHeight);
                        @endphp
                        <rect x="{{ $baseX }}" y="{{ 218 - $incomeHeight }}" width="22" height="{{ $incomeHeight }}" rx="7" fill="#49D18D"></rect>
                        <rect x="{{ $baseX + 28 }}" y="{{ 218 - $expenseHeight }}" width="22" height="{{ $expenseHeight }}" rx="7" fill="#DC2626"></rect>
                        <text x="{{ $baseX + 23 }}" y="240" text-anchor="middle" font-size="12" fill="#475569">{{ $point['month'] }}</text>
                    @endforeach
                </svg>
            </div>

            <div class="chart-legend">
                <span><i class="legend-dot income"></i> Total Sales</span>
                <span><i class="legend-dot expense"></i> Total Revenue</span>
            </div>
        </section>

        <section class="panel panel-top-products">
            <div class="panel-head compact">
                <div>
                    <h3>Top Products</h3>
                    <p>Produk dengan penjualan tertinggi</p>
                </div>
                <span class="panel-chip">{{ count($topSellingProducts) }} items</span>
            </div>

            <div class="top-products-list">
                @forelse($topSellingProducts as $product)
                    <article class="top-product-row">
                        <div class="top-product-thumb">
                            {{ strtoupper(substr($product['name'], 0, 1)) }}
                        </div>
                        <div class="top-product-info">
                            <strong>{{ $product['name'] }}</strong>
                            <span>{{ $product['category'] }} • {{ $product['quantity'] }} unit</span>
                        </div>
                        <div class="top-product-value">Rp {{ number_format($product['amount'], 0, ',', '.') }}</div>
                    </article>
                @empty
                    <div class="empty-state">Belum ada transaksi produk untuk ditampilkan.</div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="bottom-grid">
        <section class="panel">
            <div class="panel-head compact">
                <div>
                    <h3>Customer Orders</h3>
                    <p>{{ $reportMonth }}</p>
                </div>
                <div class="orders-stat">
                    <strong>{{ number_format($summary['transactions_count'], 0, ',', '.') }}</strong>
                    <span>Transaksi</span>
                </div>
            </div>

            <div class="orders-summary">
                <div>
                    <span>Income Transactions</span>
                    <strong>{{ $summary['income_count'] }}</strong>
                </div>
                <div>
                    <span>Expense Transactions</span>
                    <strong>{{ $summary['expense_count'] }}</strong>
                </div>
                <div>
                    <span>Total Sold Units</span>
                    <strong>{{ number_format($summary['total_sold_units'], 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="trend-line">
                <svg viewBox="0 0 520 120" preserveAspectRatio="none" class="trend-svg">
                    <path d="M 0 92 C 45 90, 60 58, 95 60 S 150 82, 185 66 S 245 42, 280 48 S 335 80, 370 70 S 430 44, 465 52 S 500 34, 520 30" fill="none" stroke="#A78BFA" stroke-width="4" stroke-linecap="round"></path>
                    <path d="M 0 92 C 45 90, 60 58, 95 60 S 150 82, 185 66 S 245 42, 280 48 S 335 80, 370 70 S 430 44, 465 52 S 500 34, 520 30 L 520 120 L 0 120 Z" fill="url(#trendFill)" opacity="0.16"></path>
                    <defs>
                        <linearGradient id="trendFill" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#A78BFA"></stop>
                            <stop offset="100%" stop-color="#A78BFA" stop-opacity="0"></stop>
                        </linearGradient>
                    </defs>
                    <circle cx="185" cy="66" r="5.5" fill="#fff" stroke="#A78BFA" stroke-width="3"></circle>
                    <circle cx="370" cy="70" r="5.5" fill="#fff" stroke="#A78BFA" stroke-width="3"></circle>
                    <circle cx="520" cy="30" r="5.5" fill="#fff" stroke="#A78BFA" stroke-width="3"></circle>
                </svg>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head compact">
                <div>
                    <h3>Sales by Categories</h3>
                    <p>Komposisi pengeluaran bulan ini</p>
                </div>
                <div class="panel-chip soft">{{ count($expenseDistribution) }} kategori</div>
            </div>

            <div class="pie-layout">
                <svg viewBox="0 0 250 250" class="pie-svg">
                    @forelse($pieSlices as $slice)
                        <path d="{{ $slice['path'] }}" fill="{{ $slice['color'] }}" stroke="#fff" stroke-width="2"></path>
                    @empty
                        <circle cx="125" cy="125" r="100" fill="#E5E7EB"></circle>
                    @endforelse
                </svg>

                <div class="pie-legend">
                    @foreach($expenseDistribution as $index => $item)
                        @php $sliceColor = $pieSlices[$index]['color'] ?? '#9CA3AF'; @endphp
                        <div class="pie-legend-row">
                            <span class="legend-swatch" style="background: {{ $sliceColor }}"></span>
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <small>{{ $item['percentage'] }}% • Rp {{ number_format($item['amount'], 0, ',', '.') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <div class="history-grid">
        <section class="panel">
            <div class="panel-head compact">
                <div>
                    <h3>Customer Orders</h3>
                    <p>Riwayat transaksi terbaru</p>
                </div>
            </div>

            <div class="history-list">
                @forelse($recentTransactions as $transaction)
                    @php $isIncome = $transaction['type'] === 'income'; @endphp
                    <article class="history-item {{ $isIncome ? 'income' : 'expense' }}">
                        <div class="history-main">
                            <strong>{{ $transaction['item_name'] }}</strong>
                            <p>{{ $transaction['category'] }}{{ $transaction['notes'] ? ' • '.$transaction['notes'] : '' }}</p>
                            <small>{{ $transaction['date'] }}</small>
                        </div>
                        <div class="history-side">
                            <span class="pill {{ $isIncome ? 'income' : 'expense' }}">{{ $isIncome ? 'Income' : 'Expense' }}</span>
                            <strong>{{ $isIncome ? '+' : '-' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</strong>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">Belum ada transaksi pada periode ini.</div>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <div class="panel-head compact">
                <div>
                    <h3>Stock Updates</h3>
                    <p>Riwayat stok yang berubah</p>
                </div>
            </div>

            <div class="history-list">
                @forelse($recentStockHistory as $history)
                    @php $isIncome = $history['type'] === 'income'; @endphp
                    <article class="history-item {{ $isIncome ? 'income' : 'expense' }}">
                        <div class="history-main">
                            <strong>{{ $history['product_name'] }}</strong>
                            <p>{{ $history['date'] }}</p>
                            <small>{{ $isIncome ? 'Barang keluar' : 'Barang masuk' }}</small>
                        </div>
                        <div class="history-side">
                            <span class="pill {{ $isIncome ? 'income' : 'expense' }}">{{ $isIncome ? '-' : '+' }} {{ $history['quantity'] ?? 0 }} unit</span>
                            <strong>{{ $isIncome ? '-' : '+' }} Rp {{ number_format($history['amount'], 0, ',', '.') }}</strong>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">Belum ada update stok pada periode ini.</div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="panel stock-strip">
        <div class="panel-head compact">
            <div>
                <h3>Top Stocks</h3>
                <p>Produk dengan stok terbesar dan nilai inventori tertinggi</p>
            </div>
        </div>

        <div class="stock-grid">
            @foreach($topStocks as $product)
                <article class="stock-card {{ $product['status'] }}">
                    <strong>{{ $product['name'] }}</strong>
                    <span>{{ $product['stock'] }} unit</span>
                    <small>Rp {{ number_format($product['value'], 0, ',', '.') }}</small>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.reports-shell {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.reports-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    padding: 24px;
    border-radius: 28px;
    background: linear-gradient(135deg, #f8fafc 0%, #edf7ef 100%);
    border: 1px solid #e8ecf2;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
}

.reports-kicker {
    margin: 0 0 6px;
    color: #0e5c2e;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
    font-size: 11px;
}

.reports-header h2 {
    margin: 0;
    font-size: 30px;
    color: #101828;
}

.reports-description {
    margin: 10px 0 0;
    color: #475467;
    max-width: 760px;
}

.reports-toolbar {
    display: flex;
    gap: 10px;
    align-items: end;
    flex-wrap: wrap;
}

.reports-filter {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.reports-filter label {
    font-size: 12px;
    font-weight: 700;
    color: #344054;
}

.reports-filter select,
.reports-pdf {
    height: 46px;
    border-radius: 14px;
    border: 1px solid #d0d5dd;
    background: #fff;
    padding: 0 14px;
    font-weight: 700;
    color: #101828;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.reports-pdf {
    background: #0e5c2e;
    border-color: #0e5c2e;
    color: #fff;
    padding: 0 16px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.metric-card {
    background: #fff;
    border-radius: 24px;
    padding: 18px;
    border: 1px solid #e8ecf2;
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.05);
}

.metric-card.success { border-color: #b9e3ca; }
.metric-card.info { border-color: #c7d2fe; }
.metric-card.primary { border-color: #bfd7ff; }
.metric-card.warning { border-color: #f7d58a; }

.metric-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #667085;
    font-size: 13px;
    font-weight: 700;
}

.metric-top strong {
    color: #0e5c2e;
    font-size: 12px;
}

.metric-value {
    margin-top: 14px;
    font-size: 30px;
    line-height: 1.1;
    font-weight: 800;
    color: #101828;
}

.metric-card p {
    margin: 8px 0 0;
    color: #667085;
    font-size: 13px;
}

.analytics-grid,
.bottom-grid,
.history-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.panel {
    background: #fff;
    border-radius: 28px;
    border: 1px solid #e8ecf2;
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
    padding: 18px;
}

.panel-head {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 12px;
}

.panel-head.compact {
    align-items: center;
}

.panel h3 {
    margin: 0;
    font-size: 20px;
    color: #101828;
}

.panel p {
    margin: 4px 0 0;
    color: #667085;
}

.panel-summary {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.panel-summary span,
.orders-summary span {
    display: block;
    color: #667085;
    font-size: 12px;
}

.panel-summary strong,
.orders-summary strong,
.orders-stat strong {
    display: block;
    color: #101828;
    font-size: 18px;
}

.panel-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: #eef7f1;
    color: #0e5c2e;
    font-weight: 800;
    font-size: 12px;
}

.panel-chip.soft {
    background: #eef2ff;
    color: #3730a3;
}

.bar-chart-wrap {
    overflow-x: auto;
}

.bar-chart-svg,
.trend-svg,
.pie-svg {
    width: 100%;
    height: auto;
    display: block;
}

.chart-legend {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 10px;
    color: #475467;
    font-size: 13px;
}

.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 6px;
}

.legend-dot.income { background: #49D18D; }
.legend-dot.expense { background: #DC2626; }

.top-products-list,
.history-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.top-product-row,
.history-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    padding: 14px 16px;
    border-radius: 20px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.top-product-row {
    padding: 12px 14px;
}

.top-product-row strong,
.history-item strong {
    color: #101828;
}

.top-product-thumb {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: linear-gradient(135deg, #0e5c2e, #49d18d);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    flex: 0 0 auto;
}

.top-product-info {
    flex: 1 1 auto;
}

.top-product-info span,
.history-item p,
.history-item small,
.top-product-value {
    color: #667085;
}

.top-product-value,
.history-side {
    text-align: right;
    font-weight: 800;
    color: #101828;
}

.empty-state {
    padding: 18px;
    border: 1px dashed #d0d5dd;
    border-radius: 18px;
    text-align: center;
    color: #667085;
    background: #fcfcfd;
}

.pie-layout {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 18px;
    align-items: center;
}

.pie-legend {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.pie-legend-row {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.legend-swatch {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    margin-top: 4px;
    flex: 0 0 14px;
}

.pie-legend-row strong,
.pie-legend-row small {
    display: block;
}

.pie-legend-row strong {
    color: #101828;
}

.pie-legend-row small {
    color: #667085;
}

.orders-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 14px;
}

.orders-summary > div {
    padding: 14px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.orders-stat {
    min-width: 110px;
    text-align: right;
}

.orders-stat span {
    color: #667085;
    font-size: 12px;
}

.pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
}

.pill.income {
    background: #e6f7ec;
    color: #0e5c2e;
}

.pill.expense {
    background: #fdeaea;
    color: #b42318;
}

.history-item.income {
    border-left: 4px solid #0e5c2e;
}

.history-item.expense {
    border-left: 4px solid #dc2626;
}

.history-main {
    min-width: 0;
}

.stock-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.stock-card {
    padding: 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.stock-card.normal { border-color: #b9e3ca; }
.stock-card.warning { border-color: #f7d58a; }
.stock-card.critical { border-color: #f1b1b1; }

.stock-card strong,
.stock-card span,
.stock-card small {
    color: #101828;
}

.stock-card span,
.stock-card small {
    color: #667085;
}

@media (max-width: 1200px) {
    .metrics-grid,
    .stock-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 1024px) {
    .analytics-grid,
    .bottom-grid,
    .history-grid,
    .pie-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .reports-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .metrics-grid,
    .stock-grid,
    .orders-summary {
        grid-template-columns: 1fr;
    }

    .panel-head,
    .history-item,
    .top-product-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .history-side,
    .top-product-value,
    .orders-stat {
        text-align: left;
    }
}
</style>
@endpush
