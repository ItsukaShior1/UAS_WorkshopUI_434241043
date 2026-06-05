<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 18px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 11px;
            line-height: 1.45;
        }
        .header {
            background: linear-gradient(135deg, #0e5c2e 0%, #0b3d21 100%);
            color: #fff;
            padding: 18px 20px;
            border-radius: 14px;
            margin-bottom: 14px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .header p { margin: 6px 0 0; opacity: .9; }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 12px;
        }
        .card-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-spacing: 10px;
            margin-bottom: 14px;
        }
        .card {
            display: table-cell;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            vertical-align: top;
        }
        .card span {
            display: block;
            color: #6b7280;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 6px;
        }
        .card strong { font-size: 16px; }
        .section {
            margin-top: 12px;
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            page-break-inside: avoid;
        }
        .section h2 {
            margin: 0 0 8px;
            font-size: 14px;
        }
        .chart-table { width: 100%; border-collapse: collapse; }
        .chart-table td { vertical-align: bottom; text-align: center; padding: 4px; }
        .bar-wrap { height: 140px; position: relative; }
        .bar { width: 100%; border-radius: 6px 6px 0 0; }
        .bar-income { background: #49D18D; }
        .bar-expense { background: #DC2626; }
        .legend { margin-top: 8px; }
        .legend span { margin-right: 14px; }
        .pie-layout { width: 100%; }
        .pie-svg { width: 220px; height: 220px; display: inline-block; vertical-align: top; }
        .pie-legend { display: inline-block; width: calc(100% - 240px); vertical-align: top; padding-left: 10px; }
        .legend-row { margin-bottom: 8px; }
        .dot { display: inline-block; width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; }
        .list { width: 100%; border-collapse: collapse; }
        .list td { padding: 8px 6px; border-bottom: 1px solid #eef2f7; vertical-align: top; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 9px; font-weight: 700; }
        .income-badge { background: #e6f7ec; color: #0e5c2e; }
        .expense-badge { background: #fdeaea; color: #b42318; }
        .muted { color: #6b7280; }
        .two-col { width: 100%; }
        .two-col td { width: 50%; vertical-align: top; }
        .stock-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 10px;
            font-size: 9px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Bulan {{ $reportMonth }} • Dibuat {{ $generatedAt }}</p>
    </div>

    <div class="card-grid">
        <div class="card"><span>Pemasukan</span><strong>Rp {{ number_format($summary['income'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Pengeluaran</span><strong>Rp {{ number_format($summary['expense'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Saldo</span><strong>Rp {{ number_format($summary['balance'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Transaksi</span><strong>{{ $summary['transactions_count'] }}</strong></div>
    </div>

    <table class="two-col" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div class="section">
                    <h2>Pemasukan vs Pengeluaran</h2>
                    <table class="chart-table" cellspacing="0" cellpadding="0">
                        <tr>
                            @foreach($chartData as $point)
                                @php
                                    $incomeHeight = max(10, (int) round(($point['income'] / max(1, collect($chartData)->max('income'))) * 120));
                                    $expenseHeight = max(10, (int) round(($point['expense'] / max(1, collect($chartData)->max('expense'))) * 120));
                                @endphp
                                <td>
                                    <div class="bar-wrap">
                                        <div style="position:absolute; left:10px; right:25px; bottom:0; height:{{ $incomeHeight }}px;" class="bar bar-income"></div>
                                        <div style="position:absolute; left:34px; right:1px; bottom:0; height:{{ $expenseHeight }}px;" class="bar bar-expense"></div>
                                    </div>
                                    <div class="muted">{{ $point['month'] }}</div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                    <div class="legend"><span><span class="dot" style="background:#49D18D"></span>Pemasukan</span><span><span class="dot" style="background:#DC2626"></span>Pengeluaran</span></div>
                </div>
            </td>
            <td>
                <div class="section">
                    <h2>Distribusi Pengeluaran</h2>
                    <div class="pie-layout">
                        <svg viewBox="0 0 250 250" class="pie-svg">
                            @foreach($pieSlices as $slice)
                                <path d="{{ $slice['path'] }}" fill="{{ $slice['color'] }}" stroke="#fff" stroke-width="2"></path>
                            @endforeach
                        </svg>
                        <div class="pie-legend">
                            @foreach($expenseDistribution as $index => $item)
                                @php $sliceColor = $pieSlices[$index]['color'] ?? '#9CA3AF'; @endphp
                                <div class="legend-row"><span class="dot" style="background:{{ $sliceColor }}"></span>{{ $item['name'] }} <strong>{{ $item['percentage'] }}%</strong> <span class="muted">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="two-col" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div class="section">
                    <h2>Riwayat Transaksi</h2>
                    <table class="list">
                        @foreach($recentTransactions as $transaction)
                            @php $isIncome = $transaction['type'] === 'income'; @endphp
                            <tr>
                                <td>
                                    <strong>{{ $transaction['item_name'] }}</strong><br>
                                    <span class="muted">{{ $transaction['category'] }} • {{ $transaction['date'] }}</span>
                                </td>
                                <td style="text-align:right; white-space:nowrap;">
                                    <span class="badge {{ $isIncome ? 'income-badge' : 'expense-badge' }}">{{ $isIncome ? 'Masuk' : 'Keluar' }}</span><br>
                                    {{ $isIncome ? '+' : '-' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </td>
            <td>
                <div class="section">
                    <h2>Riwayat Stok</h2>
                    <table class="list">
                        @foreach($recentStockHistory as $history)
                            @php $isIncome = $history['type'] === 'income'; @endphp
                            <tr>
                                <td>
                                    <strong>{{ $history['product_name'] }}</strong><br>
                                    <span class="muted">{{ $history['date'] }}</span>
                                </td>
                                <td style="text-align:right; white-space:nowrap;">
                                    <span class="badge {{ $isIncome ? 'expense-badge' : 'income-badge' }}">{{ $isIncome ? 'Keluar' : 'Masuk' }}</span><br>
                                    {{ $isIncome ? '-' : '+' }} {{ $history['quantity'] ?? 0 }} unit
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Produk Prioritas</h2>
        @foreach($topStocks as $product)
            <div class="stock-card">
                <strong>{{ $product['name'] }}</strong> <span class="muted">• {{ $product['stock'] }} unit</span><br>
                <span class="muted">Nilai inventori: Rp {{ number_format($product['value'], 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="footer">Laporan otomatis berdasarkan transaksi dan stok terbaru.</div>
</body>
</html>
