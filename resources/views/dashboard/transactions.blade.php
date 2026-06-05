@extends('layouts.dashboard')

@section('page-title', 'Transaksi')

@section('dashboard-content')
@php
    $transactionModalOpen = session('transaction_modal');
    $oldType = old('type', 'income');
    $oldItemSource = old('item_source', 'product');
    $oldCategory = old('category');
    $productOptionsJson = json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $placeholderImageJson = json_encode($placeholderImage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $transactionActionJson = json_encode(route('dashboard.transactions.store'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $incomeCategoriesJson = json_encode($incomeCategories ?? ['Penjualan', 'Investasi', 'Pinjaman', 'Lainnya'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $expenseCategoriesJson = json_encode($expenseCategories ?? ['Bahan Baku', 'Gaji', 'Sewa', 'Utilitas', 'Pemasaran', 'Lainnya'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $oldStateJson = json_encode([
        'type' => $oldType,
        'item_source' => $oldItemSource,
        'product_id' => old('product_id'),
        'custom_item_name' => old('custom_item_name'),
        'amount' => old('amount'),
        'quantity' => old('quantity'),
        'category' => $oldCategory,
        'notes' => old('notes'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<div class="transaction-page">
    <div class="transaction-hero">
        <div>
            <p class="transaction-eyebrow">Catat arus kas</p>
            <h2>Tambah transaksi dengan cepat</h2>
            <p class="transaction-copy">Pilih barang dari stok atau isi manual untuk transaksi khusus. Setiap transaksi menyimpan thumbnail barang kecil seperti referensi yang kamu kirim.</p>
        </div>
        <button type="button" class="transaction-add-button" data-open-transaction-modal="true">+ Transaksi Baru</button>
    </div>

    @if(session('success'))
        <div class="transaction-alert success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="transaction-alert danger">Ada data transaksi yang belum valid. Cek kembali formnya.</div>
    @endif

    <div class="transaction-summary-grid">
        <article class="transaction-summary-card income">
            <span>Jumlah Pemasukan</span>
            <strong>Rp {{ number_format($summary['income_total'] ?? 0, 0, ',', '.') }}</strong>
        </article>
        <article class="transaction-summary-card expense">
            <span>Jumlah Pengeluaran</span>
            <strong>Rp {{ number_format($summary['expense_total'] ?? 0, 0, ',', '.') }}</strong>
        </article>
        <article class="transaction-summary-card total">
            <span>Total Transaksi</span>
            <strong>{{ $summary['count'] ?? count($transactions) }}</strong>
        </article>
    </div>

    <div class="transaction-section-header">
        <div>
            <h3>Riwayat Transaksi</h3>
            <p>{{ count($transactions) }} transaksi tersimpan</p>
        </div>
    </div>

    <div class="transactions-list">
        @forelse($transactions as $transaction)
            @php
                $isIncome = $transaction['type'] === 'income';
                $amountPrefix = $isIncome ? '+' : '-';
                $amountClass = $isIncome ? 'income' : 'expense';
                $badgeLabel = $isIncome ? 'Pemasukan' : 'Pengeluaran';
                $quantityLabel = $transaction['quantity'] ? $transaction['quantity'].' unit' : null;
                $unitPriceLabel = $transaction['unit_price'] ? 'Rp '.number_format($transaction['unit_price'], 0, ',', '.') . ' / unit' : null;
            @endphp
            <article class="transaction-card {{ $amountClass }}">
                <div class="transaction-thumb-wrap">
                    <img src="{{ $transaction['image_url'] ?? $placeholderImage }}" alt="{{ $transaction['item_name'] }}" class="transaction-thumb">
                </div>
                <div class="transaction-content">
                    <div class="transaction-main-row">
                        <div>
                            <h4>{{ $transaction['item_name'] }}</h4>
                            <p>{{ $transaction['category'] }}{{ $transaction['notes'] ? ' • '.$transaction['notes'] : '' }}</p>
                        </div>
                        <span class="transaction-badge {{ $amountClass }}">{{ $badgeLabel }}</span>
                    </div>
                    @if($quantityLabel || $unitPriceLabel)
                        <div class="transaction-meta-row">
                            @if($quantityLabel)
                                <span class="transaction-meta-pill">{{ $quantityLabel }}</span>
                            @endif
                            @if($unitPriceLabel)
                                <span class="transaction-meta-pill">{{ $unitPriceLabel }}</span>
                            @endif
                        </div>
                    @endif
                    <div class="transaction-sub-row">
                        <span class="transaction-border-label">{{ $isIncome ? 'Surplus' : 'Minus' }}</span>
                        <strong class="transaction-amount {{ $amountClass }}">{{ $amountPrefix }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            </article>
        @empty
            <div class="transaction-empty-state">
                <h4>Belum ada transaksi</h4>
                <p>Tambahkan transaksi baru untuk mulai mencatat pemasukan dan pengeluaran.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="transaction-modal" id="transactionModal" aria-hidden="true">
    <div class="transaction-modal-overlay" data-close-transaction-modal="true"></div>
    <div class="transaction-modal-panel">
        <div class="transaction-modal-header">
            <div>
                <h3>Catat Transaksi</h3>
                <p>Pilih pemasukan atau pengeluaran, lalu ambil item dari stok atau isi manual.</p>
            </div>
            <button type="button" class="transaction-modal-close" data-close-transaction-modal="true">×</button>
        </div>

        <form action="{{ route('dashboard.transactions.store') }}" method="POST" class="transaction-form" id="transactionForm">
            @csrf
            <input type="hidden" name="type" id="transactionTypeInput" value="{{ old('type', 'income') }}">
            <input type="hidden" name="item_source" id="transactionItemSourceInput" value="{{ old('item_source', 'product') }}">
            <input type="hidden" name="product_id" id="transactionProductIdInput" value="{{ old('product_id') }}">
            <input type="hidden" name="category" id="transactionCategoryInput" value="{{ old('category') }}">
            <input type="hidden" id="transactionUnitPriceInput" value="0">

            <div class="transaction-type-grid">
                <button type="button" class="transaction-type-card" data-transaction-type="income">
                    <span>💰</span>
                    <strong>Pemasukan</strong>
                </button>
                <button type="button" class="transaction-type-card" data-transaction-type="expense">
                    <span>🧾</span>
                    <strong>Pengeluaran</strong>
                </button>
            </div>

            <label class="transaction-field">
                <span>Nama Item <em>*</em></span>
                <select id="transactionItemSelector" class="transaction-input">
                    <option value="">Pilih item...</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                    @endforeach
                    <option value="custom">Item Lainnya (Tulis Manual)</option>
                </select>
                @error('product_id')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label class="transaction-field" id="customItemField" style="display: none;">
                <span>Nama Item Custom <em>*</em></span>
                <input type="text" name="custom_item_name" id="customItemInput" class="transaction-input" placeholder="Contoh: Penjualan Online, Biaya Transportasi" value="{{ old('custom_item_name') }}">
                @error('custom_item_name')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label class="transaction-field" id="quantityField">
                <span>Jumlah Barang <em>*</em></span>
                <input type="number" name="quantity" id="transactionQuantityInput" class="transaction-input" placeholder="Masukkan jumlah barang terjual / dibeli" min="1" value="{{ old('quantity') }}">
                @error('quantity')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label class="transaction-field" id="amountField" style="display: none;">
                <span>Nominal Uang <em>*</em></span>
                <input type="number" name="amount" id="transactionAmountInput" class="transaction-input" placeholder="Masukkan jumlah uang" min="0" value="{{ old('amount') }}">
                @error('amount')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <div class="transaction-total-box" id="transactionTotalBox" style="display: none;">
                <div>
                    <span>Total Transaksi</span>
                    <strong id="transactionTotalPreview">Rp 0</strong>
                </div>
                <small id="transactionUnitPreview">0 unit x Rp 0</small>
            </div>

            <div class="transaction-category-block">
                <span class="transaction-block-label">Kategori</span>
                <div class="transaction-category-grid" id="transactionCategoryGrid"></div>
            </div>

            <label class="transaction-field">
                <span>Catatan (Opsional)</span>
                <textarea name="notes" class="transaction-input transaction-textarea" placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <button type="submit" class="transaction-submit-button">Simpan Transaksi</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.transaction-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.transaction-hero {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    padding: 22px;
    border-radius: 24px;
    background: linear-gradient(135deg, #0e5c2e 0%, #0b3d21 100%);
    color: #fff;
    box-shadow: 0 18px 40px rgba(7, 37, 19, 0.24);
}

.transaction-eyebrow {
    margin: 0 0 8px;
    font-size: 12px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    opacity: 0.8;
}

.transaction-hero h2 {
    margin: 0;
    font-size: 28px;
    line-height: 1.1;
}

.transaction-copy {
    margin: 12px 0 0;
    max-width: 660px;
    color: rgba(255, 255, 255, 0.86);
}

.transaction-add-button,
.transaction-submit-button,
.transaction-type-card {
    border: 0;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}

.transaction-add-button {
    padding: 14px 18px;
    background: #fff;
    color: #0e5c2e;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
    white-space: nowrap;
}

.transaction-add-button:hover,
.transaction-submit-button:hover,
.transaction-type-card:hover {
    transform: translateY(-1px);
}

.transaction-alert {
    padding: 14px 16px;
    border-radius: 16px;
    font-weight: 500;
}

.transaction-alert.success {
    background: #e6f7ec;
    color: #0e5c2e;
}

.transaction-alert.danger {
    background: #fdeaea;
    color: #b42318;
}

.transaction-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.transaction-summary-card {
    padding: 16px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e7e7e7;
    box-shadow: 0 10px 28px rgba(13, 18, 28, 0.05);
}

.transaction-summary-card span {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6c7280;
}

.transaction-summary-card strong {
    font-size: 22px;
    color: #101828;
}

.transaction-summary-card.income {
    border-color: #b8dfca;
    background: linear-gradient(180deg, #f4fbf7 0%, #fff 100%);
}

.transaction-summary-card.expense {
    border-color: #f1b1b1;
    background: linear-gradient(180deg, #fff5f5 0%, #fff 100%);
}

.transaction-summary-card.total {
    border-color: #d8e0ea;
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
}

.transaction-section-header h3 {
    margin: 0;
    font-size: 20px;
}

.transaction-section-header p {
    margin: 4px 0 0;
    color: #6c7280;
}

.transactions-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.transaction-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 16px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e8eaee;
    box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
    border-left-width: 4px;
}

.transaction-card.income {
    border-left-color: #0e5c2e;
}

.transaction-card.expense {
    border-left-color: #dc2626;
}

.transaction-thumb-wrap {
    width: 52px;
    height: 52px;
    flex: 0 0 52px;
    border-radius: 16px;
    overflow: hidden;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
}

.transaction-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.transaction-content {
    flex: 1;
    min-width: 0;
}

.transaction-main-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.transaction-main-row h4 {
    margin: 0;
    font-size: 17px;
    color: #101828;
}

.transaction-main-row p {
    margin: 6px 0 0;
    color: #667085;
    font-size: 13px;
}

.transaction-badge {
    padding: 8px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.transaction-badge.income {
    color: #0e5c2e;
    background: #e6f7ec;
}

.transaction-badge.expense {
    color: #b42318;
    background: #fdeaea;
}

.transaction-sub-row {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.transaction-border-label {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid;
}

.transaction-card.income .transaction-border-label {
    color: #0e5c2e;
    background: #f4fbf7;
    border-color: rgba(14, 92, 46, 0.18);
}

.transaction-card.expense .transaction-border-label {
    color: #b42318;
    background: #fff5f5;
    border-color: rgba(220, 38, 38, 0.18);
}

.transaction-amount {
    font-size: 16px;
    font-weight: 800;
}

.transaction-amount.income {
    color: #0e5c2e;
}

.transaction-amount.expense {
    color: #dc2626;
}

.transaction-empty-state {
    padding: 28px;
    border-radius: 20px;
    background: #fff;
    border: 1px dashed #d7dde6;
    text-align: center;
}

.transaction-empty-state h4 {
    margin: 0 0 8px;
    font-size: 18px;
}

.transaction-empty-state p {
    margin: 0;
    color: #667085;
}

.transaction-modal {
    position: fixed;
    inset: 0;
    z-index: 60;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.transaction-modal.is-open {
    display: flex;
}

.transaction-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.56);
}

.transaction-modal-panel {
    position: relative;
    z-index: 1;
    width: min(100%, 760px);
    max-height: calc(100vh - 40px);
    overflow: auto;
    border-radius: 28px;
    background: #fff;
    box-shadow: 0 28px 70px rgba(15, 23, 42, 0.28);
    padding: 22px;
}

.transaction-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 18px;
}

.transaction-modal-header h3 {
    margin: 0;
    font-size: 24px;
}

.transaction-modal-header p {
    margin: 6px 0 0;
    color: #667085;
}

.transaction-modal-close {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 14px;
    background: #f2f4f7;
    color: #101828;
    font-size: 28px;
    line-height: 1;
    cursor: pointer;
}

.transaction-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.transaction-type-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.transaction-type-card {
    min-height: 84px;
    background: #eef1f0;
    color: #101828;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 17px;
}

.transaction-type-card.active.income {
    background: #45d18a;
    color: #fff;
}

.transaction-type-card.active.expense {
    background: #e23b47;
    color: #fff;
}

.transaction-field {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.transaction-field span,
.transaction-block-label {
    font-size: 14px;
    font-weight: 600;
    color: #101828;
}

.transaction-field em {
    color: #dc3545;
    font-style: normal;
    margin-left: 4px;
}

.transaction-input {
    width: 100%;
    min-height: 54px;
    border: 1.5px solid #b7bdc8;
    border-radius: 18px;
    padding: 0 16px;
    font-size: 15px;
    color: #101828;
    outline: none;
    transition: border-color 0.18s ease, box-shadow 0.18s ease;
    background: #fff;
}

.transaction-input:focus {
    border-color: #0e5c2e;
    box-shadow: 0 0 0 4px rgba(14, 92, 46, 0.12);
}

.transaction-textarea {
    min-height: 120px;
    padding-top: 16px;
    resize: vertical;
}

.transaction-field small {
    color: #b42318;
    font-size: 12px;
}

.transaction-total-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.transaction-total-box span {
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6c7280;
    margin-bottom: 4px;
}

.transaction-total-box strong {
    font-size: 18px;
    color: #101828;
}

.transaction-total-box small {
    color: #667085;
    font-weight: 600;
}

.transaction-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.transaction-meta-pill {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    background: #f4f7fb;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.transaction-category-block {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.transaction-category-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.transaction-category-button {
    min-height: 52px;
    border: 0;
    border-radius: 16px;
    background: #eef1f0;
    color: #101828;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.18s ease, color 0.18s ease, transform 0.18s ease;
}

.transaction-category-button:hover {
    transform: translateY(-1px);
}

.transaction-category-button.active.income {
    background: #0e5c2e;
    color: #fff;
}

.transaction-category-button.active.expense {
    background: #0e5c2e;
    color: #fff;
}

.transaction-submit-button {
    width: 100%;
    min-height: 56px;
    background: linear-gradient(135deg, #0e5c2e 0%, #0b3d21 100%);
    color: #fff;
    font-size: 16px;
    box-shadow: 0 16px 28px rgba(14, 92, 46, 0.24);
}

body.modal-open {
    overflow: hidden;
}

@media (max-width: 1024px) {
    .transaction-summary-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .transaction-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .transaction-summary-grid,
    .transaction-type-grid,
    .transaction-category-grid {
        grid-template-columns: 1fr;
    }

    .transaction-main-row,
    .transaction-sub-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .transaction-modal-panel {
        padding: 18px;
        border-radius: 22px;
    }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const transactionModal = document.getElementById('transactionModal');
        const transactionForm = document.getElementById('transactionForm');
        const transactionAction = JSON.parse(@json($transactionActionJson));
        const products = JSON.parse(@json($productOptionsJson));
        const fallbackImage = JSON.parse(@json($placeholderImageJson));
        const oldState = JSON.parse(@json($oldStateJson));
        const transactionTypeInput = document.getElementById('transactionTypeInput');
        const itemSourceInput = document.getElementById('transactionItemSourceInput');
        const productIdInput = document.getElementById('transactionProductIdInput');
        const categoryInput = document.getElementById('transactionCategoryInput');
        const unitPriceInput = document.getElementById('transactionUnitPriceInput');
        const itemSelector = document.getElementById('transactionItemSelector');
        const customItemField = document.getElementById('customItemField');
        const customItemInput = document.getElementById('customItemInput');
        const categoryGrid = document.getElementById('transactionCategoryGrid');
        const quantityField = document.getElementById('quantityField');
        const amountField = document.getElementById('amountField');
        const quantityInput = document.getElementById('transactionQuantityInput');
        const amountInput = document.getElementById('transactionAmountInput');
        const totalBox = document.getElementById('transactionTotalBox');
        const totalPreview = document.getElementById('transactionTotalPreview');
        const unitPreview = document.getElementById('transactionUnitPreview');

        const categoriesByType = {
            income: JSON.parse(@json($incomeCategoriesJson)),
            expense: JSON.parse(@json($expenseCategoriesJson)),
        };

        function openModal(modal) {
            if (!modal) return;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            if (!document.querySelector('.transaction-modal.is-open')) {
                document.body.classList.remove('modal-open');
            }
        }

        function renderCategories(type, selectedCategory) {
            categoryGrid.innerHTML = '';
            const items = categoriesByType[type] || [];

            items.forEach((category) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `transaction-category-button ${type}${selectedCategory === category ? ' active' : ''}`;
                button.textContent = category;
                button.addEventListener('click', () => {
                    categoryInput.value = category;
                    renderCategories(type, category);
                });
                categoryGrid.appendChild(button);
            });

            if (!items.includes(selectedCategory)) {
                categoryInput.value = items[0] || '';
            } else {
                categoryInput.value = selectedCategory;
            }
        }

        function setType(type) {
            transactionTypeInput.value = type;
            document.querySelectorAll('[data-transaction-type]').forEach((button) => {
                button.classList.remove('active', 'income', 'expense');
                if (button.dataset.transactionType === type) {
                    button.classList.add('active', type);
                }
            });
            renderCategories(type, categoryInput.value || categoriesByType[type][0]);
        }

        function setItemSource(source) {
            itemSourceInput.value = source;
            if (source === 'custom') {
                customItemField.style.display = 'flex';
                quantityField.style.display = 'none';
                amountField.style.display = 'flex';
                totalBox.style.display = 'none';
                productIdInput.value = '';
            } else {
                customItemField.style.display = 'none';
                quantityField.style.display = 'flex';
                amountField.style.display = 'none';
                totalBox.style.display = 'flex';
                customItemInput.value = '';
            }
            updateTotalPreview();
        }

        function setItemPreviewFromProductId(productId) {
            const product = products.find((item) => String(item.id) === String(productId));
            return product ? product.image_url : fallbackImage;
        }

        function getSelectedProduct() {
            return products.find((item) => String(item.id) === String(itemSelector.value));
        }

        function formatCurrency(value) {
            return `Rp ${Number(value || 0).toLocaleString('id-ID')}`;
        }

        function updateTotalPreview() {
            if (itemSourceInput.value !== 'product') {
                return;
            }

            const selectedProduct = getSelectedProduct();
            const quantity = Number(quantityInput.value || 0);
            const unitPrice = Number(selectedProduct?.price || 0);
            const total = quantity * unitPrice;

            unitPriceInput.value = unitPrice;
            totalPreview.textContent = formatCurrency(total);
            unitPreview.textContent = `${quantity || 0} unit x ${formatCurrency(unitPrice)}`;
        }

        document.querySelectorAll('[data-open-transaction-modal="true"]').forEach((button) => {
            button.addEventListener('click', () => openModal(transactionModal));
        });

        document.querySelectorAll('[data-close-transaction-modal="true"]').forEach((button) => {
            button.addEventListener('click', () => closeModal(transactionModal));
        });

        document.querySelectorAll('[data-transaction-type]').forEach((button) => {
            button.addEventListener('click', () => setType(button.dataset.transactionType));
        });

        itemSelector.addEventListener('change', () => {
            const value = itemSelector.value;

            if (value === 'custom') {
                setItemSource('custom');
                productIdInput.value = '';
                return;
            }

            if (value) {
                setItemSource('product');
                productIdInput.value = value;
                unitPriceInput.value = getSelectedProduct()?.price || 0;
                customItemField.style.display = 'none';
                updateTotalPreview();
            }
        });

        quantityInput?.addEventListener('input', updateTotalPreview);

        amountInput?.addEventListener('input', () => {
            if (itemSourceInput.value === 'custom') {
                totalPreview.textContent = formatCurrency(amountInput.value);
            }
        });

        if (transactionForm) {
            transactionForm.action = transactionAction;
        }

        const initialType = oldState.type || 'income';
        const initialCategory = oldState.category || categoriesByType[initialType][0];
        setType(initialType);
        categoryInput.value = initialCategory;
        renderCategories(initialType, initialCategory);

        if (oldState.item_source === 'custom') {
            setItemSource('custom');
            itemSelector.value = 'custom';
            customItemInput.value = oldState.custom_item_name || '';
            if (oldState.amount) {
                amountInput.value = oldState.amount;
            }
        } else {
            setItemSource('product');
            if (oldState.product_id) {
                itemSelector.value = String(oldState.product_id);
                productIdInput.value = String(oldState.product_id);
                unitPriceInput.value = getSelectedProduct()?.price || 0;
                if (oldState.quantity) {
                    quantityInput.value = oldState.quantity;
                }
            }
        }

        updateTotalPreview();

        if ({{ json_encode((bool) $transactionModalOpen) }}) {
            openModal(transactionModal);
        }

        if (itemSelector.value && itemSelector.value !== 'custom') {
            productIdInput.value = itemSelector.value;
        }
    });
</script>
@endpush
