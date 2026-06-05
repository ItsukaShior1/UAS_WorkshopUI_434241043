<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Services\ReportDataBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private function transactionFallbackImage(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><rect width="96" height="96" rx="24" fill="#0e5c2e"/><path d="M24 34h48v28H24z" fill="#ffffff" fill-opacity=".14" stroke="#ffffff" stroke-opacity=".45"/><circle cx="48" cy="48" r="12" fill="#ffffff" fill-opacity=".9"/><path d="M36 48h24" stroke="#0e5c2e" stroke-width="4" stroke-linecap="round"/><path d="M24 28c7 0 7 6 14 6s7-6 14-6 7 6 14 6 7-6 14-6" fill="none" stroke="#b8dfca" stroke-width="3" stroke-linecap="round"/></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    private function dataUrlToPayload(string $dataUrl): array
    {
        if (! str_starts_with($dataUrl, 'data:')) {
            return ['image_data' => null, 'image_mime' => null];
        }

        $commaPosition = strpos($dataUrl, ',');

        if ($commaPosition === false) {
            return ['image_data' => null, 'image_mime' => null];
        }

        $header = substr($dataUrl, 5, $commaPosition - 5);
        $mime = explode(';', $header)[0] ?: 'image/svg+xml';

        return [
            'image_data' => substr($dataUrl, $commaPosition + 1) ?: null,
            'image_mime' => $mime,
        ];
    }

    private function communityPosts(): array
    {
        return CommunityPost::query()
            ->latest('id')
            ->get()
            ->map(fn (CommunityPost $post) => $this->decorateCommunityPost($post))
            ->all();
    }

    private function decorateCommunityPost(CommunityPost $post): array
    {
        $contentWords = str_word_count(strip_tags($post->content));
        $readMinutes = max(1, (int) ceil($contentWords / 180));

        return [
            'id' => $post->id,
            'type' => $post->category,
            'author' => $post->author_name,
            'author_role' => $post->author_role ?? 'UMKM',
            'avatar' => $post->avatar_url ?: 'https://ui-avatars.com/api/?name='.urlencode($post->author_name).'&background=0f5a34&color=fff',
            'cover' => $post->cover_url ?: $post->avatar_url ?: 'https://ui-avatars.com/api/?name='.urlencode($post->title).'&background=0f5a34&color=fff&size=640',
            'category' => $post->category,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'date' => $post->created_at?->translatedFormat('d F Y') ?? '',
            'read_time' => $readMinutes.' menit baca',
            'likes' => (int) $post->likes_count,
            'comments' => (int) $post->comments_count,
            'views' => number_format(((int) $post->likes_count + (int) $post->comments_count) * 7),
        ];
    }

    private function stockPlaceholderImage(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480"><defs><linearGradient id="a" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0f7a3b"/><stop offset="1" stop-color="#0f5b2f"/></linearGradient><linearGradient id="b" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f7f4e8" stop-opacity=".2"/><stop offset="1" stop-color="#ffffff" stop-opacity=".05"/></linearGradient></defs><rect width="640" height="480" rx="36" fill="url(#a)"/><circle cx="520" cy="110" r="78" fill="url(#b)"/><rect x="118" y="118" width="404" height="244" rx="28" fill="#ffffff" fill-opacity=".1" stroke="#ffffff" stroke-opacity=".16"/><rect x="166" y="170" width="148" height="116" rx="18" fill="#ffffff" fill-opacity=".16"/><rect x="326" y="170" width="148" height="116" rx="18" fill="#ffffff" fill-opacity=".16"/><path d="M210 204h52v12h-52zm0 26h74v12h-74zm0 26h44v12h-44zM370 204h52v12h-52zm0 26h74v12h-74zm0 26h44v12h-44z" fill="#ffffff" fill-opacity=".75"/><rect x="196" y="318" width="248" height="18" rx="9" fill="#ffffff" fill-opacity=".55"/><rect x="246" y="348" width="148" height="12" rx="6" fill="#ffffff" fill-opacity=".35"/></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    private function seedStockProducts(): array
    {
        return [
            ['name' => 'Baju Arsenal', 'stock' => 15, 'min' => 10, 'price' => 50000],
            ['name' => 'Keris Empu Gandri', 'stock' => 5, 'min' => 10, 'price' => 75000],
            ['name' => 'Produk C', 'stock' => 20, 'min' => 10, 'price' => 100000],
            ['name' => 'Coklat Dubai', 'stock' => 2, 'min' => 5, 'price' => 25000],
        ];
    }

    private function normalizeStockProductNames(): void
    {
        $nameMap = [
            1 => ['Product A', 'Produk A', 'Baju Arsenal'],
            2 => ['Product B', 'Produk B', 'Keris Empu Gandri'],
            4 => ['Bahan Baku X', 'Coklat Dubai'],
        ];

        foreach ($nameMap as $productId => $allowedNames) {
            $product = Product::query()->find($productId);

            if (! $product) {
                continue;
            }

            if (! in_array($product->name, $allowedNames, true)) {
                continue;
            }

            $product->name = match ($productId) {
                1 => 'Baju Arsenal',
                2 => 'Keris Empu Gandri',
                4 => 'Coklat Dubai',
                default => $product->name,
            };

            $product->save();
        }
    }

    private function imageToDataUrl(?string $imageData, ?string $imageMime): string
    {
        if (! $imageData) {
            return $this->stockPlaceholderImage();
        }

        $mime = $imageMime ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.$imageData;
    }

    private function legacyImagePayload(?string $legacyImage): array
    {
        if (! $legacyImage) {
            return ['image_data' => null, 'image_mime' => null];
        }

        if (str_starts_with($legacyImage, 'data:')) {
            $commaPosition = strpos($legacyImage, ',');

            if ($commaPosition !== false) {
                $header = substr($legacyImage, 5, $commaPosition - 5);
                $data = substr($legacyImage, $commaPosition + 1);
                $mime = explode(';', $header)[0] ?: 'image/jpeg';

                return [
                    'image_data' => $data ?: null,
                    'image_mime' => $mime,
                ];
            }
        }

        if (Storage::disk('public')->exists($legacyImage)) {
            return [
                'image_data' => base64_encode(Storage::disk('public')->get($legacyImage)),
                'image_mime' => Storage::disk('public')->mimeType($legacyImage) ?: 'image/jpeg',
            ];
        }

        return ['image_data' => null, 'image_mime' => null];
    }

    private function bootstrapStockProducts(): void
    {
        $this->normalizeStockProductNames();

        if (Product::query()->exists()) {
            return;
        }

        $legacyProducts = session()->get('dashboard.stock.products');
        $sourceProducts = is_array($legacyProducts) && $legacyProducts ? $legacyProducts : $this->seedStockProducts();

        foreach ($sourceProducts as $product) {
            $legacyImage = $product['image'] ?? null;
            $imagePayload = $this->legacyImagePayload(is_string($legacyImage) ? $legacyImage : null);

            Product::create([
                'name' => $product['name'] ?? 'Produk Baru',
                'stock' => (int) ($product['stock'] ?? 0),
                'min' => (int) ($product['min'] ?? 0),
                'price' => (int) ($product['price'] ?? 0),
                'image_data' => $imagePayload['image_data'],
                'image_mime' => $imagePayload['image_mime'],
            ]);
        }

        session()->forget('dashboard.stock.products');
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

    private function decorateStockProduct(Product $product): array
    {
        $productArray = $product->only(['id', 'name', 'stock', 'min', 'price']);
        $productArray['status'] = $this->calculateStockStatus($productArray['stock'], $productArray['min']);
        $productArray['image_url'] = $this->imageToDataUrl($product->image_data, $product->image_mime);

        return $productArray;
    }

    private function getStockProducts(): array
    {
        $this->bootstrapStockProducts();

        return Product::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Product $product) => $this->decorateStockProduct($product))
            ->all();
    }

    private function storeStockImage(Request $request): array
    {
        if (! $request->hasFile('image')) {
            return ['image_data' => null, 'image_mime' => null];
        }

        $image = $request->file('image');

        return [
            'image_data' => base64_encode(file_get_contents($image->getRealPath())),
            'image_mime' => $image->getMimeType() ?: 'image/jpeg',
        ];
    }

    private function stockSummary(): array
    {
        $collection = Product::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Product $product) => $this->decorateStockProduct($product));

        return [
            'total_products' => $collection->count(),
            'critical_count' => $collection->where('status', 'critical')->count(),
            'warning_count' => $collection->where('status', 'warning')->count(),
            'inventory_value' => (int) $collection->sum(fn (array $product) => $product['stock'] * $product['price']),
        ];
    }

    private function seedTransactions(): array
    {
        return [
            ['type' => 'income', 'item_name' => 'Baju Arsenal', 'category' => 'Penjualan', 'quantity' => 10, 'unit_price' => 50000, 'amount' => 500000, 'notes' => 'Penjualan harian', 'product_id' => 1],
            ['type' => 'expense', 'item_name' => 'Keris Empu Gandri', 'category' => 'Bahan Baku', 'quantity' => 4, 'unit_price' => 75000, 'amount' => 300000, 'notes' => 'Pembelian bahan', 'product_id' => 2],
            ['type' => 'income', 'item_name' => 'Item Lainnya', 'category' => 'Pemasaran', 'amount' => 200000, 'notes' => 'Order khusus', 'product_id' => null],
            ['type' => 'expense', 'item_name' => 'Coklat Dubai', 'category' => 'Utilitas', 'quantity' => 6, 'unit_price' => 25000, 'amount' => 150000, 'notes' => 'Pengeluaran operasional', 'product_id' => 4],
        ];
    }

    private function normalizeTransactionRecords(): void
    {
        $productNameMap = [
            1 => 'Baju Arsenal',
            2 => 'Keris Empu Gandri',
            4 => 'Coklat Dubai',
        ];

        $placeholderNames = ['Product A', 'Produk A', 'Product B', 'Produk B', 'Bahan Baku X', 'Coklat Dubai', 'Keris Empu Gandri', 'Baju Arsenal'];

        foreach ($productNameMap as $productId => $productName) {
            Transaction::query()
                ->where('product_id', $productId)
                ->whereIn('item_name', $placeholderNames)
                ->update(['item_name' => $productName]);
        }
    }

    private function backfillTransactionMetrics(): void
    {
        Transaction::query()
            ->whereNotNull('product_id')
            ->where(function ($query) {
                $query->whereNull('quantity')
                    ->orWhereNull('unit_price');
            })
            ->get()
            ->each(function (Transaction $transaction) {
                $product = Product::query()->find($transaction->product_id);

                if (! $product) {
                    return;
                }

                $unitPrice = (int) $product->price;
                $quantity = $unitPrice > 0 ? max(1, (int) round(((int) $transaction->amount) / $unitPrice)) : null;

                if ($quantity === null) {
                    return;
                }

                $transaction->quantity = $quantity;
                $transaction->unit_price = $unitPrice;
                $transaction->save();
            });
    }

    private function transactionImageFromProduct(?Product $product): array
    {
        if (! $product || ! $product->image_data) {
            return [
                'item_image_data' => $this->dataUrlToPayload($this->transactionFallbackImage())['image_data'],
                'item_image_mime' => $this->dataUrlToPayload($this->transactionFallbackImage())['image_mime'],
            ];
        }

        return [
            'item_image_data' => $product->image_data,
            'item_image_mime' => $product->image_mime,
        ];
    }

    private function bootstrapTransactions(): void
    {
        $this->normalizeTransactionRecords();
        $this->backfillTransactionMetrics();

        if (Transaction::query()->exists()) {
            return;
        }

        $this->bootstrapStockProducts();
        $products = Product::query()->orderBy('id')->get()->keyBy('id');

        foreach ($this->seedTransactions() as $transaction) {
            $product = $transaction['product_id'] ? $products->get($transaction['product_id']) : null;
            $imagePayload = $this->transactionImageFromProduct($product);

            Transaction::create([
                'type' => $transaction['type'],
                'item_name' => $transaction['item_name'],
                'category' => $transaction['category'],
                'amount' => $transaction['amount'],
                'quantity' => $transaction['quantity'] ?? null,
                'unit_price' => $transaction['unit_price'] ?? null,
                'notes' => $transaction['notes'],
                'product_id' => $transaction['product_id'],
                'item_image_data' => $imagePayload['item_image_data'],
                'item_image_mime' => $imagePayload['item_image_mime'],
            ]);
        }
    }

    private function decorateTransaction(Transaction $transaction): array
    {
        return [
            'id' => $transaction->id,
            'type' => $transaction->type,
            'item_name' => $transaction->item_name,
            'category' => $transaction->category,
            'amount' => (int) $transaction->amount,
            'quantity' => $transaction->quantity ? (int) $transaction->quantity : null,
            'unit_price' => $transaction->unit_price ? (int) $transaction->unit_price : null,
            'notes' => $transaction->notes,
            'product_id' => $transaction->product_id,
            'image_url' => $transaction->item_image_data
                ? 'data:'.($transaction->item_image_mime ?: 'image/svg+xml').';base64,'.$transaction->item_image_data
                : $this->transactionFallbackImage(),
            'direction' => $transaction->type === 'income' ? 'positive' : 'negative',
        ];
    }

    private function getTransactions(): array
    {
        $this->bootstrapTransactions();

        return Transaction::query()
            ->latest()
            ->get()
            ->map(fn (Transaction $transaction) => $this->decorateTransaction($transaction))
            ->all();
    }

    private function transactionSummary(): array
    {
        $transactions = Transaction::query()->get();

        return [
            'income_total' => (int) $transactions->where('type', 'income')->sum('amount'),
            'expense_total' => (int) $transactions->where('type', 'expense')->sum('amount'),
            'count' => $transactions->count(),
        ];
    }

    private function findCommunityPost(int $postId): array
    {
        $post = CommunityPost::query()->find($postId);

        abort_if(! $post, 404);

        return $this->decorateCommunityPost($post);
    }

    private function getCommentsForPost(CommunityPost $post): array
    {
        return $post->comments()
            ->latest('id')
            ->get()
            ->map(fn (CommunityComment $comment) => [
                'id' => $comment->id,
                'author' => $comment->author_name,
                'time' => $comment->created_at?->diffForHumans() ?? '',
                'text' => $comment->body,
            ])
            ->all();
    }

    /**
     * Show dashboard home
     */
    public function home()
    {
        $user = Auth::user();

        $this->bootstrapTransactions();
        $this->bootstrapStockProducts();

        $transactionSummary = $this->transactionSummary();
        $stockSummary = $this->stockSummary();

        $income = (int) $transactionSummary['income_total'];
        $expense = (int) $transactionSummary['expense_total'];
        $balance = $income - $expense;

        $now = now();
        $previousMonth = $now->copy()->subMonth();
        $previousBalance = (int) Transaction::query()
            ->where('created_at', '<', $previousMonth->copy()->endOfMonth())
            ->get()
            ->reduce(function (int $carry, Transaction $transaction) {
                return $carry + ($transaction->type === 'income' ? (int) $transaction->amount : -(int) $transaction->amount);
            }, 0);

        $trend = $previousBalance > 0
            ? (int) round((($balance - $previousBalance) / $previousBalance) * 100)
            : ($balance > 0 ? 100 : 0);

        $monthLabel = $now->translatedFormat('F Y');

        $criticalStock = Product::query()
            ->get()
            ->map(fn (Product $product) => $this->decorateStockProduct($product))
            ->whereIn('status', ['critical', 'warning'])
            ->values()
            ->all();

        $transactions = collect($this->getTransactions())
            ->take(4)
            ->map(function (array $transaction) use ($now) {
                $createdAt = Transaction::query()->find($transaction['id'])?->created_at;

                return [
                    'type' => $transaction['type'],
                    'name' => $transaction['item_name'],
                    'category' => $transaction['category'],
                    'amount' => $transaction['amount'],
                    'date' => $createdAt ? $createdAt->format('Y-m-d') : $now->format('Y-m-d'),
                ];
            })
            ->all();

        return view('dashboard.home', compact(
            'user',
            'balance',
            'income',
            'expense',
            'trend',
            'monthLabel',
            'transactions',
            'criticalStock',
            'stockSummary',
            'transactionSummary'
        ));
    }

    /**
     * Show transactions page
     */
    public function transactions()
    {
        $user = Auth::user();

        $products = $this->getStockProducts();
        $transactions = $this->getTransactions();
        $summary = $this->transactionSummary();
        $placeholderImage = $this->transactionFallbackImage();
        $transactionTypes = [
            ['value' => 'income', 'label' => 'Pemasukan'],
            ['value' => 'expense', 'label' => 'Pengeluaran'],
        ];
        $incomeCategories = ['Penjualan', 'Pemasaran', 'Investasi', 'Lainnya'];
        $expenseCategories = ['Bahan Baku', 'Gaji', 'Sewa', 'Utilitas', 'Pemasaran', 'Lainnya'];

        return view('dashboard.transactions', compact('user', 'products', 'transactions', 'summary', 'placeholderImage', 'transactionTypes', 'incomeCategories', 'expenseCategories'));
    }

    public function transactionsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'item_source' => 'required|in:product,custom',
            'product_id' => 'required_if:item_source,product|nullable|integer|exists:products,id',
            'custom_item_name' => 'nullable|string|max:120',
            'quantity' => 'required_if:item_source,product|nullable|integer|min:1',
            'amount' => 'required_if:item_source,custom|nullable|integer|min:0',
            'category' => 'required|string|max:60',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('transaction_modal', true);
        }

        $product = null;
        $itemName = trim((string) $request->input('custom_item_name'));

        if ($request->input('item_source') === 'product') {
            $product = Product::query()->findOrFail((int) $request->input('product_id'));
            $itemName = $product->name;
        }

        if ($request->input('item_source') === 'custom' && $itemName === '') {
            return back()
                ->withErrors(['custom_item_name' => 'Nama item custom wajib diisi.'])
                ->withInput()
                ->with('transaction_modal', true);
        }

        $imagePayload = $this->transactionImageFromProduct($product);
        $quantity = $request->input('item_source') === 'product' ? (int) $request->input('quantity') : null;
        $unitPrice = $product ? (int) $product->price : null;
        $amount = $request->input('item_source') === 'product'
            ? ($quantity * ($unitPrice ?? 0))
            : (int) $request->input('amount');

        if ($product && $quantity !== null) {
            if ($request->input('type') === 'income' && $product->stock < $quantity) {
                return back()
                    ->withErrors(['quantity' => 'Stok barang tidak cukup untuk transaksi ini.'])
                    ->withInput()
                    ->with('transaction_modal', true);
            }

            DB::transaction(function () use ($request, $product, $itemName, $imagePayload, $quantity, $unitPrice, $amount) {
                $product->stock = $request->input('type') === 'income'
                    ? $product->stock - $quantity
                    : $product->stock + $quantity;
                $product->save();

                Transaction::create([
                    'type' => $request->input('type'),
                    'item_name' => $itemName,
                    'category' => $request->input('category'),
                    'amount' => $amount,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'notes' => $request->input('notes'),
                    'product_id' => $product->id,
                    'item_image_data' => $imagePayload['item_image_data'],
                    'item_image_mime' => $imagePayload['item_image_mime'],
                ]);
            });

            return redirect()->route('dashboard.transactions')->with('success', 'Transaksi berhasil disimpan dan stok sudah diperbarui.');
        }

        Transaction::create([
            'type' => $request->input('type'),
            'item_name' => $itemName,
            'category' => $request->input('category'),
            'amount' => $amount,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'notes' => $request->input('notes'),
            'product_id' => $product?->id,
            'item_image_data' => $imagePayload['item_image_data'],
            'item_image_mime' => $imagePayload['item_image_mime'],
        ]);

        return redirect()->route('dashboard.transactions')->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Show stock page
     */
    public function stock()
    {
        $user = Auth::user();

        $products = $this->getStockProducts();
        $summary = $this->stockSummary();
        $placeholderImage = $this->stockPlaceholderImage();

        return view('dashboard.stock', compact('user', 'products', 'summary', 'placeholderImage'));
    }

    public function stockShow(int $product)
    {
        $user = Auth::user();
        $productModel = Product::query()->findOrFail($product);

        $productData = $this->decorateStockProduct($productModel);
        $transactions = Transaction::query()
            ->where('product_id', $productModel->id)
            ->latest('created_at')
            ->get();

        $history = $transactions->map(function (Transaction $transaction) {
            $isIncome = $transaction->type === 'income';

            return [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => (int) $transaction->amount,
                'quantity' => $transaction->quantity ? (int) $transaction->quantity : null,
                'unit_price' => $transaction->unit_price ? (int) $transaction->unit_price : null,
                'category' => $transaction->category,
                'notes' => $transaction->notes,
                'date' => $transaction->created_at?->format('d M Y, H:i'),
                'direction' => $isIncome ? 'minus' : 'plus',
                'label' => $isIncome ? 'Penjualan' : 'Restock',
            ];
        })->all();

        $summary = [
            'transactions_count' => count($history),
            'stock_moved_out' => (int) $transactions->where('type', 'income')->sum('quantity'),
            'stock_moved_in' => (int) $transactions->where('type', 'expense')->sum('quantity'),
            'turnover_value' => (int) $transactions->sum('amount'),
        ];

        $statusLabel = [
            'critical' => 'Stok Kritis',
            'warning' => 'Stok Rendah',
            'normal' => 'Stok Aman',
        ][$productData['status']] ?? 'Stok Aman';

        return view('dashboard.stock-detail', compact('user', 'productData', 'history', 'summary', 'statusLabel'));
    }

    public function stockStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'stock' => 'required|integer|min:0',
            'min' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('stock_modal', 'add');
        }

        $imagePayload = $this->storeStockImage($request);

        Product::create([
            'name' => $request->input('name'),
            'stock' => (int) $request->input('stock'),
            'min' => (int) $request->input('min'),
            'price' => (int) $request->input('price'),
            'image_data' => $imagePayload['image_data'],
            'image_mime' => $imagePayload['image_mime'],
        ]);

        return redirect()->route('dashboard.stock')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function stockUpdate(Request $request, int $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'stock' => 'required|integer|min:0',
            'min' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('stock_modal', 'edit')
                ->with('stock_modal_product_id', $product);
        }

        $productModel = Product::query()->findOrFail($product);
        $imagePayload = $this->storeStockImage($request);

        $productModel->fill([
            'name' => $request->input('name'),
            'stock' => (int) $request->input('stock'),
            'min' => (int) $request->input('min'),
            'price' => (int) $request->input('price'),
        ]);

        if ($imagePayload['image_data']) {
            $productModel->image_data = $imagePayload['image_data'];
            $productModel->image_mime = $imagePayload['image_mime'];
        }

        $productModel->save();

        return redirect()->route('dashboard.stock')->with('success', 'Produk berhasil diperbarui.');
    }

    public function stockDestroy(int $product)
    {
        Product::query()->findOrFail($product)->delete();

        return redirect()->route('dashboard.stock')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Show reports page
     */
    public function reports(Request $request, ReportDataBuilder $reportDataBuilder)
    {
        $user = Auth::user();

        $report = $reportDataBuilder->build($request->input('month'));

        return view('dashboard.reports', array_merge(compact('user'), $report));
    }

    /**
     * Show insights page
     */
    public function insights()
    {
        $user = Auth::user();
        
        $trends = [
            ['title' => 'Penjualan Online Meningkat', 'description' => 'Penjualan melalui channel online naik 45% dibanding bulan lalu', 'icon' => '📈'],
            ['title' => 'Stok Efisien', 'description' => 'Tingkat perputaran stok meningkat 30% dengan pengelolaan yang lebih baik', 'icon' => '📦'],
            ['title' => 'Margin Keuntungan Optimal', 'description' => 'Margin keuntungan mencapai 35% - lebih tinggi dari rata-rata UMKM', 'icon' => '💰'],
        ];
        
        return view('dashboard.insights', compact('user', 'trends'));
    }

    /**
     * Show community page
     */
    public function community()
    {
        $user = Auth::user();

        $posts = $this->communityPosts();

        return view('dashboard.community', compact('user', 'posts'));
    }

    public function communityCreate()
    {
        $user = Auth::user();

        $categories = ['Kisah Sukses', 'Tips & Trik', 'Tantangan', 'Lainnya'];

        return view('dashboard.community-create', compact('user', 'categories'));
    }

    public function communityStore(Request $request)
    {
        $categories = ['Kisah Sukses', 'Tips & Trik', 'Tantangan', 'Lainnya'];

        $validator = Validator::make($request->all(), [
            'category' => ['required', 'string', Rule::in($categories)],
            'title' => 'required|string|max:160',
            'content' => 'required|string|min:100',
        ], [
            'category.required' => 'Pilih salah satu kategori artikel.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'title.required' => 'Judul artikel wajib diisi.',
            'title.max' => 'Judul maksimal 160 karakter.',
            'content.required' => 'Cerita Anda wajib diisi.',
            'content.min' => 'Cerita minimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('create_modal', true);
        }

        $user = Auth::user();
        $content = (string) $request->input('content');
        $title = (string) $request->input('title');

        $excerpt = mb_strimwidth(preg_replace('/\s+/', ' ', strip_tags($content)), 0, 180, '…');
        $authorName = $user?->name ?: 'UMKM Bookify';
        $avatar = 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&background=0f5a34&color=fff';

        CommunityPost::create([
            'user_id' => $user?->id,
            'author_name' => $authorName,
            'author_role' => 'UMKM',
            'avatar_url' => $avatar,
            'cover_url' => $avatar,
            'category' => (string) $request->input('category'),
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
        ]);

        return redirect()->route('dashboard.community')->with('success', 'Artikel berhasil dibagikan ke komunitas.');
    }

    public function communityShow(int $post)
    {
        $user = Auth::user();

        $postModel = CommunityPost::query()->findOrFail($post);
        $post = $this->decorateCommunityPost($postModel);
        $comments = $this->getCommentsForPost($postModel);

        return view('dashboard.community-detail', compact('user', 'post', 'comments'));
    }

    public function communityCommentStore(Request $request, int $post)
    {
        $postModel = CommunityPost::query()->findOrFail($post);

        $validator = Validator::make($request->all(), [
            'body' => 'required|string|min:2|max:500',
        ], [
            'body.required' => 'Komentar tidak boleh kosong.',
            'body.min' => 'Komentar terlalu pendek.',
            'body.max' => 'Komentar maksimal 500 karakter.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('comment_modal', true);
        }

        $user = Auth::user();
        $authorName = $user?->name ?: 'Pengguna Bookify';

        DB::transaction(function () use ($postModel, $request, $user, $authorName) {
            CommunityComment::create([
                'post_id' => $postModel->id,
                'user_id' => $user?->id,
                'author_name' => $authorName,
                'body' => (string) $request->input('body'),
            ]);

            $postModel->increment('comments_count');
        });

        return redirect()
            ->to(route('dashboard.community.show', $postModel->id).'#comments')
            ->with('success', 'Komentar berhasil dikirim.');
    }

    public function communityLikeToggle(int $post)
    {
        $postModel = CommunityPost::query()->findOrFail($post);

        $postModel->increment('likes_count');

        if (request()->wantsJson()) {
            return response()->json([
                'likes' => (int) $postModel->fresh()->likes_count,
            ]);
        }

        return back();
    }
}
