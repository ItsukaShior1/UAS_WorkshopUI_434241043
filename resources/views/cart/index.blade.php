@extends('layouts.dashboard')

@section('page-title', 'Keranjang')

@section('dashboard-content')
<div class="cart-shell">
    <h2>Keranjang ({{ $cart->total_quantity }})</h2>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="cart-toolbar">
        <div class="cart-toolbar-left">
            <label class="cart-select-all">
                <input type="checkbox" id="selectAllToggle" {{ $cart->items->isNotEmpty() && $cart->selectedItems->count() === $cart->items->count() ? 'checked' : '' }}>
                <span>Pilih Semua ({{ $cart->items->count() }})</span>
            </label>
            <form method="POST" action="{{ route('cart.clear') }}" class="cart-toolbar-form">
                @csrf
                <button class="btn-text-danger" data-confirm="Kosongkan semua item?">Kosongkan</button>
            </form>
        </div>
    </div>

    <div class="cart-wrap">
        <div class="cart-items-list">
            @forelse ($cart->items as $item)
                <div class="cart-row" data-cart-row data-item-id="{{ $item->id }}" data-line-total="{{ $item->line_total }}" data-line-qty="{{ $item->quantity }}" data-original-price="{{ $item->original_price ?: $item->price }}">
                    <div class="cart-row-check">
                        <input type="checkbox"
                               class="cart-item-check"
                               data-item-check
                               data-toggle-url="{{ route('cart.toggle-select', $item) }}"
                               {{ $item->selected ? 'checked' : '' }}>
                    </div>

                    <div class="cart-row-img">
                        @if ($item->image_data_uri)
                            <img src="{{ $item->image_data_uri }}" alt="{{ $item->name }}">
                        @elseif($item->is_subscription)
                            <div class="cart-row-img-fallback subscription"><i data-lucide="crown"></i></div>
                        @else
                            <div class="cart-row-img-fallback"><i data-lucide="package"></i></div>
                        @endif
                    </div>

                    <div class="cart-row-body">
                        <strong class="cart-row-title">{{ $item->name }}</strong>
                        @if ($item->is_subscription)
                            <span class="cart-row-tag"><i data-lucide="zap"></i> Langganan</span>
                        @endif
                        <p class="cart-row-subtitle">{{ $item->subtitle }}</p>
                    </div>

                    <div class="cart-row-price">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</div>

                    <div class="cart-row-qty">
                        @if ($item->is_subscription)
                            <span class="cart-row-qty-static">×1</span>
                        @else
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="cart-row-qty-form">
                                @csrf @method('PUT')
                                <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" class="admin-input qty-input">
                                <button class="btn-icon" type="submit" title="Perbarui"><i data-lucide="check"></i></button>
                            </form>
                        @endif
                    </div>

                    <div class="cart-row-total">Rp {{ number_format($item->line_total, 0, ',', '.') }}</div>

                    <div class="cart-row-actions">
                        <form method="POST" action="{{ route('cart.destroy', $item) }}">
                            @csrf @method('DELETE')
                            <button class="btn-icon btn-icon-danger" type="submit" data-confirm="Hapus item ini?" title="Hapus">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="cart-empty-state">
                    <i data-lucide="shopping-bag"></i>
                    <h3>Keranjang kosong</h3>
                    <p>Tambahkan paket langganan atau produk dari halaman masing-masing.</p>
                    <a href="{{ route('subscription.index') }}" class="btn-link">Lihat Paket Langganan</a>
                </div>
            @endforelse
        </div>

        @if ($cart->items->isNotEmpty())
            <aside class="cart-summary" data-cart-summary>
                <h3>Ringkasan Belanja</h3>
                <div class="summary-row">
                    <span>Total Item Dipilih</span>
                    <strong data-summary-qty>{{ $cart->selected_quantity }}</strong>
                </div>
                @php
                    $cartOriginal = (float) $cart->selectedItems->sum(fn ($i) => ($i->original_price ?: $i->price) * $i->quantity);
                    $cartSaved = max(0, $cartOriginal - (float) $cart->selected_subtotal);
                @endphp
                @if ($cartSaved > 0)
                    <div class="summary-row summary-saved">
                        <span>Anda hemat</span>
                        <strong data-summary-saved>Rp {{ number_format($cartSaved, 0, ',', '.') }}</strong>
                    </div>
                @endif
                <div class="summary-row total">
                    <span>Subtotal Dipilih</span>
                    <strong data-summary-subtotal>Rp {{ number_format($cart->selected_subtotal, 0, ',', '.') }}</strong>
                </div>
                <p class="cart-summary-note">Hanya item dengan centang yang akan di-checkout.</p>
                <form method="POST" action="{{ route('cart.checkout') }}" class="cart-checkout-form">
                    @csrf
                    <label>Metode Pembayaran</label>
                    <select name="payment_method" class="admin-input" required>
                        <option value="ewallet">E-Wallet</option>
                        <option value="va">Virtual Account</option>
                        <option value="qris">QRIS</option>
                    </select>
                    <button class="btn-primary"
                            type="submit"
                            data-checkout-btn
                            {{ $cart->has_selected ? '' : 'disabled' }}>
                        <i data-lucide="shopping-cart"></i> Checkout (<span data-checkout-count>{{ $cart->selected_quantity }}</span> item)
                    </button>
                </form>
            </aside>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-shell { display: flex; flex-direction: column; gap: 16px; }
.cart-shell h2 { margin: 0; color: #123122; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-success { background: #dcfce7; color: #166534; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert ul { margin: 0; padding-left: 18px; }

/* Toolbar */
.cart-toolbar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 18px;
}
.cart-toolbar-left { display: flex; align-items: center; gap: 16px; }
.cart-select-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #123122;
    cursor: pointer;
    user-select: none;
}
.cart-select-all input,
.cart-item-check {
    width: 20px;
    height: 20px;
    accent-color: #0f5a34;
    cursor: pointer;
}
.cart-toolbar-form { display: inline; }
.btn-text-danger { background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 600; font-family: inherit; }

/* Layout */
.cart-wrap { display: grid; grid-template-columns: 1fr 320px; gap: 16px; align-items: flex-start; }
@media (max-width: 900px) { .cart-wrap { grid-template-columns: 1fr; } }

/* Cart row (Shopee-like) */
.cart-row {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    display: grid;
    grid-template-columns: 40px 80px 1fr 110px 110px 110px 40px;
    gap: 14px;
    align-items: center;
    padding: 14px 18px;
    margin-bottom: 12px;
    transition: box-shadow .15s ease, border-color .15s ease;
}
.cart-row:hover { border-color: #cbd5e1; box-shadow: 0 2px 10px rgba(15, 23, 42, .04); }
.cart-row-check { display: flex; justify-content: center; }
.cart-row-img { display: flex; align-items: center; justify-content: center; }
.cart-row-img img,
.cart-row-img-fallback {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    object-fit: cover;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
}
.cart-row-img-fallback.subscription { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; }
.cart-row-img-fallback i { width: 28px; height: 28px; }

.cart-row-body { min-width: 0; }
.cart-row-title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #123122;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cart-row-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 999px;
    background: #fef3c7;
    color: #b45309;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 4px;
}
.cart-row-tag i { width: 11px; height: 11px; }
.cart-row-subtitle { margin: 0; color: #5d7d6f; font-size: 12px; }

.cart-row-price { color: #475569; font-size: 13px; font-weight: 500; text-align: center; }

.cart-row-qty { display: flex; justify-content: center; }
.cart-row-qty-form { display: flex; gap: 4px; align-items: center; }
.qty-input { width: 56px; padding: 6px 8px; text-align: center; }
.cart-row-qty-static { color: #475569; font-weight: 600; }

.cart-row-total { color: #dc2626; font-size: 15px; font-weight: 700; text-align: center; }

.cart-row-actions { display: flex; justify-content: center; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; transition: background .15s ease; }
.btn-icon:hover { background: #f1f5f9; }
.btn-icon i { width: 14px; height: 14px; color: #475569; }
.btn-icon-danger:hover { background: #fee2e2; }
.btn-icon-danger:hover i { color: #dc2626; }

.cart-empty-state {
    background: #fff;
    border: 1px dashed #cbd5e1;
    border-radius: 14px;
    padding: 50px 20px;
    text-align: center;
    color: #94a3b8;
}
.cart-empty-state i { width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 10px; }
.cart-empty-state h3 { margin: 0 0 6px; color: #123122; }
.cart-empty-state p { margin: 0 0 14px; }
.btn-link { color: #0f5a34; font-weight: 600; text-decoration: none; }
.btn-link:hover { text-decoration: underline; }

/* Summary */
.cart-summary {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    position: sticky;
    top: 16px;
}
.cart-summary h3 { margin: 0 0 14px; color: #123122; }
.summary-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
.summary-row.total {
    color: #dc2626;
    font-size: 18px;
    font-weight: 700;
    border-top: 1px solid #f1f5f9;
    padding-top: 12px;
    margin-top: 6px;
}
.summary-saved { color: #d97706; font-weight: 600; }
.summary-saved strong { color: #d97706; }
.cart-summary-note {
    margin: 8px 0 14px;
    font-size: 12px;
    color: #94a3b8;
    font-style: italic;
}
.cart-checkout-form { display: flex; flex-direction: column; gap: 10px; }
.cart-checkout-form label { font-weight: 600; font-size: 13px; }
.cart-checkout-form .btn-primary { width: 100%; justify-content: center; padding: 14px; }
.btn-primary { display: inline-flex; align-items: center; gap: 6px; background: #0f5a34; color: #fff; padding: 12px 18px; border-radius: 12px; border: none; font-weight: 700; cursor: pointer; font-size: 15px; transition: background .15s ease, transform .12s ease; }
.btn-primary:hover:not(:disabled) { background: #0a4326; }
.btn-primary:active:not(:disabled) { transform: scale(.98); }
.btn-primary:disabled { opacity: .5; cursor: not-allowed; }
.btn-primary i { width: 16px; height: 16px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();

    // Confirm dialogs
    document.querySelectorAll('[data-confirm]').forEach(f => {
        f.addEventListener('submit', e => {
            if (!confirm(f.dataset.confirm)) e.preventDefault();
        });
    });

    // CSRF helper
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value;

    // Indonesian number format helper
    function fmtRp(n) {
        return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Recompute + render summary based on currently-checked rows
    function recalcSummary() {
        const rows = document.querySelectorAll('[data-cart-row]');
        let subtotal = 0;
        let originalSubtotal = 0;
        let qty = 0;
        let checkedCount = 0;
        rows.forEach(row => {
            if (row.querySelector('[data-item-check]').checked) {
                subtotal += parseFloat(row.dataset.lineTotal) || 0;
                const originalPrice = parseFloat(row.dataset.originalPrice) || parseFloat(row.dataset.lineTotal) || 0;
                originalSubtotal += originalPrice * (parseInt(row.dataset.lineQty, 10) || 0);
                qty += parseInt(row.dataset.lineQty, 10) || 0;
                checkedCount++;
            }
        });

        const summaryQty = document.querySelector('[data-summary-qty]');
        const summarySubtotal = document.querySelector('[data-summary-subtotal]');
        const summarySaved = document.querySelector('[data-summary-saved]');
        const checkoutBtn = document.querySelector('[data-checkout-btn]');
        const checkoutCount = document.querySelector('[data-checkout-count]');
        const selectAllToggle = document.getElementById('selectAllToggle');

        if (summaryQty) summaryQty.textContent = qty;
        if (summarySubtotal) summarySubtotal.textContent = fmtRp(subtotal);
        if (summarySaved) summarySaved.textContent = fmtRp(Math.max(0, originalSubtotal - subtotal));
        if (checkoutCount) checkoutCount.textContent = qty;
        if (checkoutBtn) checkoutBtn.disabled = checkedCount === 0;

        // Update select-all state
        if (selectAllToggle && rows.length > 0) {
            selectAllToggle.checked = checkedCount === rows.length;
            selectAllToggle.indeterminate = checkedCount > 0 && checkedCount < rows.length;
        }
    }

    // Persist checkbox toggle via fetch
    async function toggleItem(checkbox) {
        const url = checkbox.dataset.toggleUrl;
        checkbox.disabled = true;
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });
            const data = await res.json();
            checkbox.checked = data.selected;
        } catch (err) {
            // Silent fail — re-check on page refresh
            console.error(err);
        } finally {
            checkbox.disabled = false;
            recalcSummary();
        }
    }

    // Per-item toggle
    document.querySelectorAll('[data-item-check]').forEach(cb => {
        cb.addEventListener('change', function () {
            toggleItem(cb);
        });
    });

    // Select-all
    const selectAllToggle = document.getElementById('selectAllToggle');
    if (selectAllToggle) {
        selectAllToggle.addEventListener('change', async function () {
            const desired = selectAllToggle.checked;
            // Optimistic UI: tick all rows, then sync server
            document.querySelectorAll('[data-item-check]').forEach(cb => {
                cb.checked = desired;
            });
            recalcSummary();

            try {
                const res = await fetch('{{ route('cart.select-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ selected: desired })
                });
                const data = await res.json();
                const checkoutBtn = document.querySelector('[data-checkout-btn]');
                if (checkoutBtn) checkoutBtn.disabled = !data.has_selected;
            } catch (err) {
                console.error(err);
                window.location.reload();
            }
        });
    }

    recalcSummary();
});
</script>
@endpush
