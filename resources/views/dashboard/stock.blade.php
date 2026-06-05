@extends('layouts.dashboard')

@section('page-title', 'Stok')

@section('dashboard-content')
@php
    $stockModal = session('stock_modal');
    $stockModalProductId = session('stock_modal_product_id');
    $hasStockErrors = $errors->any();
    $stockProductsJson = json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $placeholderImageJson = json_encode($placeholderImage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $modalStateJson = json_encode(session('stock_modal'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $modalProductIdJson = json_encode(session('stock_modal_product_id'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $editActionBaseJson = json_encode(url('/dashboard/stock'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $oldInputStateJson = json_encode([
        'name' => old('name'),
        'stock' => old('stock'),
        'min' => old('min'),
        'price' => old('price'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<div class="stock-page">
    <div class="stock-hero">
        <div>
            <p class="stock-eyebrow">Inventori barang</p>
            <h2>Kelola stok dengan cepat</h2>
            <p class="stock-hero-copy">Tambah, ubah, dan pantau stok barang dengan tampilan yang lebih nyaman dipakai di desktop maupun mobile. Upload gambar juga sudah didukung.</p>
        </div>
        <button type="button" class="stock-add-button" data-open-stock-modal="add">+ Produk Baru</button>
    </div>

    @if(session('success'))
        <div class="stock-alert success">{{ session('success') }}</div>
    @endif

    @if($hasStockErrors)
        <div class="stock-alert danger">
            Ada data yang belum valid. Silakan periksa kembali isian form.
        </div>
    @endif

    <div class="stock-summary-grid">
        <article class="stock-summary-card">
            <span class="stock-summary-label">Total Produk</span>
            <strong>{{ $summary['total_products'] ?? count($products) }}</strong>
        </article>
        <article class="stock-summary-card warning">
            <span class="stock-summary-label">Stok Rendah</span>
            <strong>{{ $summary['warning_count'] ?? 0 }}</strong>
        </article>
        <article class="stock-summary-card critical">
            <span class="stock-summary-label">Stok Kritis</span>
            <strong>{{ $summary['critical_count'] ?? 0 }}</strong>
        </article>
        <article class="stock-summary-card value">
            <span class="stock-summary-label">Nilai Inventori</span>
            <strong>Rp {{ number_format($summary['inventory_value'] ?? 0, 0, ',', '.') }}</strong>
        </article>
    </div>

    <div class="stock-section-header">
        <div>
            <h3>Daftar Produk</h3>
            <p>{{ count($products) }} barang tersimpan</p>
        </div>
    </div>

    <div class="stock-grid">
        @foreach($products as $product)
            @php
                $statusLabel = [
                    'critical' => 'Stok Kritis',
                    'warning' => 'Stok Rendah',
                    'normal' => 'Stok Aman',
                ][$product['status']] ?? 'Stok Aman';
            @endphp
            <article class="stock-card">
                <div class="stock-card-image-wrap">
                    <img src="{{ $product['image_url'] ?? $placeholderImage }}" alt="{{ $product['name'] }}" class="stock-card-image">
                    <span class="stock-card-badge {{ $product['status'] }}">{{ $statusLabel }}</span>
                </div>
                <div class="stock-card-body">
                    <div class="stock-card-title-row">
                        <div>
                            <h4>{{ $product['name'] }}</h4>
                            <p>Batas minimum {{ $product['min'] }} unit</p>
                        </div>
                        <div class="stock-card-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                    </div>

                    <div class="stock-card-metrics">
                        <div>
                            <span>Stok saat ini</span>
                            <strong>{{ $product['stock'] }} unit</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong>{{ $statusLabel }}</strong>
                        </div>
                    </div>

                    <div class="stock-card-actions">
                        <a href="{{ route('dashboard.stock.show', $product['id']) }}" class="stock-action-button detail" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">Detail</a>
                        <button
                            type="button"
                            class="stock-action-button edit"
                            data-open-edit-modal="true"
                            data-product='@json($product)'
                        >
                            Edit
                        </button>
                        <form action="{{ route('dashboard.stock.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Hapus {{ $product['name'] }} dari daftar stok?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="stock-action-button danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>

<div class="stock-modal" id="addStockModal" aria-hidden="true">
    <div class="stock-modal-overlay" data-close-stock-modal="true"></div>
    <div class="stock-modal-panel">
        <div class="stock-modal-header">
            <div>
                <h3>Tambah Produk Baru</h3>
                <p>Isi data produk dan upload gambar jika ada.</p>
            </div>
            <button type="button" class="stock-modal-close" data-close-stock-modal="true">×</button>
        </div>

        <form action="{{ route('dashboard.stock.store') }}" method="POST" enctype="multipart/form-data" class="stock-form">
            @csrf
            <div class="stock-form-grid">
                <label>
                    <span>Nama Produk <em>*</em></span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                    @error('name')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Jumlah Stok <em>*</em></span>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="0" placeholder="Masukkan jumlah stok" required>
                    @error('stock')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Batas Minimum <em>*</em></span>
                    <input type="number" name="min" value="{{ old('min') }}" min="0" placeholder="Masukkan batas minimum" required>
                    @error('min')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Harga <em>*</em></span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" placeholder="Masukkan harga" required>
                    @error('price')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label class="stock-upload-field">
                    <span>Gambar Produk</span>
                    <input type="file" name="image" accept="image/*" id="addStockImageInput">
                    <small>Format jpg, jpeg, png, atau webp. Maksimal 2 MB.</small>
                    @error('image')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
            </div>

            <div class="stock-image-preview">
                <img src="{{ $placeholderImage }}" alt="Preview produk" id="addStockImagePreview">
            </div>

            <button type="submit" class="stock-submit-button">Simpan Produk</button>
        </form>
    </div>
</div>

<div class="stock-modal" id="editStockModal" aria-hidden="true">
    <div class="stock-modal-overlay" data-close-stock-modal="true"></div>
    <div class="stock-modal-panel">
        <div class="stock-modal-header">
            <div>
                <h3>Edit Produk</h3>
                <p>Perbarui nama, stok, harga, atau gambar produk.</p>
            </div>
            <button type="button" class="stock-modal-close" data-close-stock-modal="true">×</button>
        </div>

        <form id="editStockForm" method="POST" enctype="multipart/form-data" class="stock-form">
            @csrf
            @method('PUT')
            <div class="stock-form-grid">
                <label>
                    <span>Nama Produk <em>*</em></span>
                    <input type="text" name="name" id="editStockName" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                    @error('name')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Jumlah Stok <em>*</em></span>
                    <input type="number" name="stock" id="editStockStock" value="{{ old('stock') }}" min="0" placeholder="Masukkan jumlah stok" required>
                    @error('stock')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Batas Minimum <em>*</em></span>
                    <input type="number" name="min" id="editStockMin" value="{{ old('min') }}" min="0" placeholder="Masukkan batas minimum" required>
                    @error('min')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Harga <em>*</em></span>
                    <input type="number" name="price" id="editStockPrice" value="{{ old('price') }}" min="0" placeholder="Masukkan harga" required>
                    @error('price')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label class="stock-upload-field">
                    <span>Gambar Produk</span>
                    <input type="file" name="image" accept="image/*" id="editStockImageInput">
                    <small>Pilih gambar baru jika ingin mengganti foto produk.</small>
                    @error('image')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
            </div>

            <div class="stock-image-preview">
                <img src="{{ $placeholderImage }}" alt="Preview produk" id="editStockImagePreview">
            </div>

            <button type="submit" class="stock-submit-button">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.stock-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stock-hero {
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

.stock-eyebrow {
    margin: 0 0 8px;
    font-size: 12px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    opacity: 0.8;
}

.stock-hero h2 {
    margin: 0;
    font-size: 28px;
    line-height: 1.1;
}

.stock-hero-copy {
    margin: 12px 0 0;
    max-width: 640px;
    color: rgba(255, 255, 255, 0.86);
}

.stock-add-button,
.stock-submit-button,
.stock-action-button {
    border: 0;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}

.stock-add-button {
    padding: 14px 18px;
    background: #fff;
    color: #0e5c2e;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
    white-space: nowrap;
}

.stock-add-button:hover,
.stock-submit-button:hover,
.stock-action-button:hover {
    transform: translateY(-1px);
}

.stock-alert {
    padding: 14px 16px;
    border-radius: 16px;
    font-weight: 500;
}

.stock-alert.success {
    background: #e6f7ec;
    color: #0e5c2e;
}

.stock-alert.danger {
    background: #fdeaea;
    color: #b42318;
}

.stock-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.stock-summary-card {
    padding: 16px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e7e7e7;
    box-shadow: 0 10px 28px rgba(13, 18, 28, 0.05);
}

.stock-summary-card.warning {
    border-color: #f3d58f;
    background: linear-gradient(180deg, #fffaf0 0%, #fff 100%);
}

.stock-summary-card.critical {
    border-color: #f1b1b1;
    background: linear-gradient(180deg, #fff5f5 0%, #fff 100%);
}

.stock-summary-card.value {
    border-color: #b8dfca;
    background: linear-gradient(180deg, #f4fbf7 0%, #fff 100%);
}

.stock-summary-label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6c7280;
}

.stock-summary-card strong {
    font-size: 22px;
    color: #101828;
}

.stock-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stock-section-header h3 {
    margin: 0;
    font-size: 20px;
}

.stock-section-header p {
    margin: 4px 0 0;
    color: #6c7280;
}

.stock-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.stock-card {
    overflow: hidden;
    border-radius: 24px;
    background: #fff;
    border: 1px solid #e8eaee;
    box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
}

.stock-card-image-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #f3f4f6;
}

.stock-card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.stock-card-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 8px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    backdrop-filter: blur(10px);
}

.stock-card-badge.normal {
    background: rgba(14, 92, 46, 0.9);
}

.stock-card-badge.warning {
    background: rgba(220, 149, 10, 0.92);
}

.stock-card-badge.critical {
    background: rgba(185, 28, 28, 0.92);
}

.stock-card-body {
    padding: 18px;
}

.stock-card-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.stock-card-title-row h4 {
    margin: 0;
    font-size: 18px;
    color: #101828;
}

.stock-card-title-row p {
    margin: 6px 0 0;
    color: #667085;
    font-size: 13px;
}

.stock-card-price {
    font-size: 15px;
    font-weight: 700;
    color: #0e5c2e;
    white-space: nowrap;
}

.stock-card-metrics {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.stock-card-metrics div {
    padding: 12px;
    border-radius: 16px;
    background: #f8fafc;
}

.stock-card-metrics span {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    color: #667085;
}

.stock-card-metrics strong {
    font-size: 15px;
    color: #101828;
}

.stock-card-actions {
    display: flex;
    gap: 10px;
    margin-top: 16px;
}

.stock-card-actions form {
    flex: 1;
}

.stock-action-button {
    width: 100%;
    padding: 12px 14px;
    background: #0e5c2e;
    color: #fff;
}

.stock-action-button.edit {
    background: #ebf4ef;
    color: #0e5c2e;
}

.stock-action-button.danger {
    background: #fff1f1;
    color: #b42318;
}

.stock-modal {
    position: fixed;
    inset: 0;
    z-index: 60;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.stock-modal.is-open {
    display: flex;
}

.stock-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.56);
}

.stock-modal-panel {
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

.stock-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 18px;
}

.stock-modal-header h3 {
    margin: 0;
    font-size: 24px;
}

.stock-modal-header p {
    margin: 6px 0 0;
    color: #667085;
}

.stock-modal-close {
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

.stock-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.stock-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.stock-form label {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.stock-form span {
    font-size: 14px;
    font-weight: 600;
    color: #101828;
}

.stock-form em {
    color: #dc3545;
    font-style: normal;
    margin-left: 4px;
}

.stock-form input {
    width: 100%;
    height: 54px;
    border: 1.5px solid #b7bdc8;
    border-radius: 18px;
    padding: 0 16px;
    font-size: 15px;
    color: #101828;
    outline: none;
    transition: border-color 0.18s ease, box-shadow 0.18s ease;
    background: #fff;
}

.stock-form input:focus {
    border-color: #0e5c2e;
    box-shadow: 0 0 0 4px rgba(14, 92, 46, 0.12);
}

.stock-form small {
    color: #b42318;
    font-size: 12px;
}

.stock-upload-field {
    grid-column: span 2;
}

.stock-upload-field small {
    color: #667085;
}

.stock-image-preview {
    margin-top: 4px;
    border-radius: 20px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.stock-image-preview img {
    display: block;
    width: 100%;
    max-height: 220px;
    object-fit: cover;
}

.stock-submit-button {
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
    .stock-summary-grid,
    .stock-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .stock-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .stock-summary-grid,
    .stock-grid,
    .stock-form-grid {
        grid-template-columns: 1fr;
    }

    .stock-upload-field {
        grid-column: span 1;
    }

    .stock-card-title-row,
    .stock-card-actions {
        flex-direction: column;
    }

    .stock-section-header {
        align-items: flex-start;
    }

    .stock-modal-panel {
        padding: 18px;
        border-radius: 22px;
    }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addModal = document.getElementById('addStockModal');
        const editModal = document.getElementById('editStockModal');
        const editForm = document.getElementById('editStockForm');
        const addPreview = document.getElementById('addStockImagePreview');
        const editPreview = document.getElementById('editStockImagePreview');
        const addImageInput = document.getElementById('addStockImageInput');
        const editImageInput = document.getElementById('editStockImageInput');
        const stockProducts = JSON.parse(@json($stockProductsJson));
        const placeholderImage = JSON.parse(@json($placeholderImageJson));
        const modalState = JSON.parse(@json($modalStateJson));
        const modalProductId = JSON.parse(@json($modalProductIdJson));
        const editActionBase = JSON.parse(@json($editActionBaseJson));
        const oldInputState = JSON.parse(@json($oldInputStateJson));

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
            if (!document.querySelector('.stock-modal.is-open')) {
                document.body.classList.remove('modal-open');
            }
        }

        function fillPreview(previewElement, source) {
            if (!previewElement) return;
            previewElement.src = source || placeholderImage;
        }

        function fillEditModal(product) {
            if (!product) return;

            editForm.action = `${editActionBase}/${product.id}`;
            document.getElementById('editStockName').value = product.name ?? '';
            document.getElementById('editStockStock').value = product.stock ?? '';
            document.getElementById('editStockMin').value = product.min ?? '';
            document.getElementById('editStockPrice').value = product.price ?? '';
            fillPreview(editPreview, product.image_url || placeholderImage);
        }

        document.querySelectorAll('[data-open-stock-modal="add"]').forEach((button) => {
            button.addEventListener('click', () => openModal(addModal));
            button.addEventListener('click', () => {
                const form = addModal?.querySelector('form');
                if (form) {
                    form.reset();
                }

                fillPreview(addPreview, placeholderImage);
            });
        });

        document.querySelectorAll('[data-open-edit-modal="true"]').forEach((button) => {
            button.addEventListener('click', () => {
                const product = JSON.parse(button.dataset.product);
                fillEditModal(product);
                openModal(editModal);
            });
        });

        document.querySelectorAll('[data-close-stock-modal="true"]').forEach((button) => {
            button.addEventListener('click', () => {
                closeModal(addModal);
                closeModal(editModal);
            });
        });

        addImageInput?.addEventListener('change', (event) => {
            const [file] = event.target.files || [];
            if (!file) {
                fillPreview(addPreview, placeholderImage);
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => fillPreview(addPreview, e.target.result);
            reader.readAsDataURL(file);
        });

        editImageInput?.addEventListener('change', (event) => {
            const [file] = event.target.files || [];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => fillPreview(editPreview, e.target.result);
            reader.readAsDataURL(file);
        });

        if (modalState === 'add') {
            openModal(addModal);
        }

        if (modalState === 'edit') {
            const product = stockProducts.find((item) => String(item.id) === String(modalProductId));
            if (product) {
                fillEditModal(product);
            } else {
                fillPreview(editPreview, placeholderImage);
            }

            if (oldInputState.name !== null) {
                document.getElementById('editStockName').value = oldInputState.name ?? '';
                document.getElementById('editStockStock').value = oldInputState.stock ?? '';
                document.getElementById('editStockMin').value = oldInputState.min ?? '';
                document.getElementById('editStockPrice').value = oldInputState.price ?? '';
            }

            openModal(editModal);
        }
    });
</script>
@endpush
