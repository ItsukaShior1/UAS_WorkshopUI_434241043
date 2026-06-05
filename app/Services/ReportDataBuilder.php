<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportDataBuilder
{
    public function build(?string $month = null): array
    {
        $selectedMonth = $this->normalizeMonth($month);
        $months = $this->availableMonths();
        $reportMonthLabel = $selectedMonth->translatedFormat('F Y');

        $periodTransactions = $this->transactionsForMonth($selectedMonth);
        $stockProducts = Product::query()->orderBy('id')->get();
        $stockHistory = $this->stockHistoryForMonth($selectedMonth);
        $monthlyTrend = $this->monthlyTrend($months);
        $expenseDistribution = $this->expenseDistribution($periodTransactions);
        $pieSlices = $this->pieSlices($expenseDistribution);
        $topStocks = $this->topStockProducts($stockProducts);
        $topSellingProducts = $this->topSellingProducts($periodTransactions);
        $recentTransactions = $this->recentTransactions($periodTransactions);
        $recentStockHistory = $this->recentStockHistory($selectedMonth);

        $income = (int) $periodTransactions->where('type', 'income')->sum('amount');
        $expense = (int) $periodTransactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;
        $totalSoldUnits = (int) $periodTransactions->where('type', 'income')->sum('quantity');
        $lowStockProducts = $stockProducts->filter(function (Product $product) {
            return $this->calculateStockStatus((int) $product->stock, (int) $product->min) !== 'normal';
        });

        return [
            'reportMonth' => $reportMonthLabel,
            'reportMonthKey' => $selectedMonth->format('Y-m'),
            'months' => $months->map(fn (Carbon $date) => [
                'key' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y'),
            ])->values()->all(),
            'summary' => [
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance,
                'income_count' => $periodTransactions->where('type', 'income')->count(),
                'expense_count' => $periodTransactions->where('type', 'expense')->count(),
                'transactions_count' => $periodTransactions->count(),
                'stock_items_count' => $stockProducts->count(),
                'total_sold_units' => $totalSoldUnits,
                'low_stock_count' => $lowStockProducts->count(),
            ],
            'highlightCards' => [
                [
                    'label' => 'Total Penjualan',
                    'value' => number_format($totalSoldUnits, 0, ',', '.'),
                    'hint' => 'unit terjual bulan ini',
                    'trend' => '+'.number_format(max(1, $periodTransactions->where('type', 'income')->count()), 0, ',', '.'),
                    'tone' => 'success',
                ],
                [
                    'label' => 'Produk Aktif',
                    'value' => number_format($stockProducts->count(), 0, ',', '.'),
                    'hint' => 'item terdaftar di stok',
                    'trend' => 'live',
                    'tone' => 'info',
                ],
                [
                    'label' => 'Total Revenue',
                    'value' => 'Rp '.number_format($income, 0, ',', '.'),
                    'hint' => 'pemasukan bulan ini',
                    'trend' => '+'.($income > 0 ? round(($income / max(1, $expense + $income)) * 100) : 0).'%',
                    'tone' => 'primary',
                ],
                [
                    'label' => 'Stok Menipis',
                    'value' => number_format($lowStockProducts->count(), 0, ',', '.'),
                    'hint' => 'produk perlu perhatian',
                    'trend' => $lowStockProducts->count() > 0 ? 'cek stok' : 'aman',
                    'tone' => 'warning',
                ],
            ],
            'chartData' => $monthlyTrend,
            'expenseDistribution' => $expenseDistribution,
            'pieSlices' => $pieSlices,
            'recentTransactions' => $recentTransactions,
            'recentStockHistory' => $recentStockHistory,
            'topStocks' => $topStocks,
            'topSellingProducts' => $topSellingProducts,
            'stockHistory' => $stockHistory,
            'products' => $stockProducts->map(fn (Product $product) => $this->decorateProduct($product))->values()->all(),
        ];
    }

    private function normalizeMonth(?string $month): Carbon
    {
        if ($month) {
            try {
                return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            } catch (\Throwable) {
                // fall through to latest available month
            }
        }

        $latest = Transaction::query()->latest('created_at')->value('created_at');

        return $latest ? Carbon::parse($latest)->startOfMonth() : now()->startOfMonth();
    }

    private function availableMonths(): Collection
    {
        $months = Transaction::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-01") as month_start')
            ->groupBy('month_start')
            ->orderByDesc('month_start')
            ->limit(6)
            ->pluck('month_start')
            ->map(fn (string $monthStart) => Carbon::parse($monthStart)->startOfMonth());

        if ($months->isEmpty()) {
            $months = collect(range(0, 5))->map(fn (int $offset) => now()->copy()->subMonths($offset)->startOfMonth());
        }

        return $months->sort()->values();
    }

    private function transactionsForMonth(Carbon $month): Collection
    {
        return Transaction::query()
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->latest('created_at')
            ->get();
    }

    private function monthlyTrend(Collection $months): array
    {
        return $months->map(function (Carbon $month) {
            $transactions = $this->transactionsForMonth($month);

            return [
                'month' => $month->translatedFormat('M'),
                'income' => (int) $transactions->where('type', 'income')->sum('amount'),
                'expense' => (int) $transactions->where('type', 'expense')->sum('amount'),
            ];
        })->values()->all();
    }

    private function expenseDistribution(Collection $transactions): array
    {
        $expenses = $transactions->where('type', 'expense');
        $total = (int) $expenses->sum('amount');

        $grouped = $expenses
            ->groupBy('category')
            ->map(function (Collection $items, string $category) use ($total) {
                $amount = (int) $items->sum('amount');

                return [
                    'name' => $category,
                    'amount' => $amount,
                    'percentage' => $total > 0 ? (int) round(($amount / $total) * 100) : 0,
                ];
            })
            ->sortByDesc('amount')
            ->values();

        return $grouped->isEmpty()
            ? [
                ['name' => 'Bahan Baku', 'amount' => 0, 'percentage' => 0],
                ['name' => 'Gaji', 'amount' => 0, 'percentage' => 0],
                ['name' => 'Lainnya', 'amount' => 0, 'percentage' => 0],
            ]
            : $grouped->all();
    }

    private function pieSlices(array $expenseDistribution): array
    {
        $palette = ['#074F2A', '#49D18D', '#F59E0B', '#DC2626', '#7C3AED', '#0EA5E9'];
        $total = array_sum(array_map(fn (array $item) => (int) $item['amount'], $expenseDistribution));

        if ($total <= 0) {
            return [];
        }

        $start = -90.0;
        $slices = [];

        foreach ($expenseDistribution as $index => $item) {
            $amount = (int) $item['amount'];
            $percentage = $total > 0 ? ($amount / $total) : 0;
            $sweep = 360.0 * $percentage;
            $end = $start + $sweep;
            $slices[] = [
                'name' => $item['name'],
                'amount' => $amount,
                'percentage' => (int) round($percentage * 100),
                'color' => $palette[$index % count($palette)],
                'path' => $this->describePieSlice(125, 125, 100, $start, $end),
            ];
            $start = $end;
        }

        return $slices;
    }

    private function describePieSlice(int $cx, int $cy, int $radius, float $startAngle, float $endAngle): string
    {
        $start = $this->polarToCartesian($cx, $cy, $radius, $endAngle);
        $end = $this->polarToCartesian($cx, $cy, $radius, $startAngle);
        $largeArcFlag = ($endAngle - $startAngle) <= 180 ? 0 : 1;

        return sprintf(
            'M %d %d L %.2f %.2f A %d %d 0 %d 0 %.2f %.2f Z',
            $cx,
            $cy,
            $start['x'],
            $start['y'],
            $radius,
            $radius,
            $largeArcFlag,
            $end['x'],
            $end['y']
        );
    }

    private function polarToCartesian(int $centerX, int $centerY, int $radius, float $angleInDegrees): array
    {
        $angleInRadians = deg2rad($angleInDegrees - 90);

        return [
            'x' => $centerX + ($radius * cos($angleInRadians)),
            'y' => $centerY + ($radius * sin($angleInRadians)),
        ];
    }

    private function recentTransactions(Collection $transactions): array
    {
        return $transactions->take(6)->map(function (Transaction $transaction) {
            return [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'item_name' => $transaction->item_name,
                'category' => $transaction->category,
                'amount' => (int) $transaction->amount,
                'quantity' => $transaction->quantity ? (int) $transaction->quantity : null,
                'unit_price' => $transaction->unit_price ? (int) $transaction->unit_price : null,
                'notes' => $transaction->notes,
                'date' => $transaction->created_at?->format('d M Y'),
            ];
        })->values()->all();
    }

    private function recentStockHistory(Carbon $month): array
    {
        return Transaction::query()
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(function (Transaction $transaction) {
                return [
                    'product_name' => $transaction->item_name,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity ? (int) $transaction->quantity : null,
                    'amount' => (int) $transaction->amount,
                    'date' => $transaction->created_at?->format('d M Y, H:i'),
                ];
            })
            ->values()
            ->all();
    }

    private function stockHistoryForMonth(Carbon $month): array
    {
        return Transaction::query()
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->latest('created_at')
            ->get()
            ->map(function (Transaction $transaction) {
                return [
                    'product_name' => $transaction->item_name,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity ? (int) $transaction->quantity : null,
                    'unit_price' => $transaction->unit_price ? (int) $transaction->unit_price : null,
                    'amount' => (int) $transaction->amount,
                    'date' => $transaction->created_at?->format('d M Y, H:i'),
                ];
            })
            ->values()
            ->all();
    }

    private function topStockProducts(Collection $products): array
    {
        return $products
            ->map(fn (Product $product) => $this->decorateProduct($product))
            ->sortByDesc('stock')
            ->take(4)
            ->values()
            ->all();
    }

    private function topSellingProducts(Collection $transactions): array
    {
        return $transactions
            ->where('type', 'income')
            ->whereNotNull('product_id')
            ->groupBy('item_name')
            ->map(function (Collection $items, string $name) {
                $quantity = (int) $items->sum('quantity');
                $amount = (int) $items->sum('amount');

                return [
                    'name' => $name,
                    'quantity' => $quantity,
                    'amount' => $amount,
                    'category' => $items->first()?->category ?? 'Penjualan',
                ];
            })
            ->sortByDesc('amount')
            ->take(4)
            ->values()
            ->all();
    }

    private function decorateProduct(Product $product): array
    {
        $status = $this->calculateStockStatus((int) $product->stock, (int) $product->min);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'stock' => (int) $product->stock,
            'min' => (int) $product->min,
            'price' => (int) $product->price,
            'status' => $status,
            'value' => (int) $product->stock * (int) $product->price,
        ];
    }

    private function calculateStockStatus(int $stock, int $minimum): string
    {
        if ($stock <= 0) {
            return 'critical';
        }

        if ($stock <= max(1, (int) ceil($minimum / 2))) {
            return 'critical';
        }

        if ($stock <= $minimum) {
            return 'warning';
        }

        return 'normal';
    }
}
