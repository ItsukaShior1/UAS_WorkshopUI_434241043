@extends('layouts.dashboard')

@section('page-title', 'Edit Post')

@section('dashboard-content')
@if ($errors->any())
    <div class="flash-error">
        <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

<div id="communityEdit" class="page-content active">
    <header class="create-header">
        <a href="{{ route('dashboard.community.show', $post['id']) }}" class="back-btn" aria-label="Kembali">
            <i data-lucide="arrow-left"></i>
        </a>
        <div>
            <h2>Edit Cerita Anda</h2>
            <p>Perbarui isi artikel Anda untuk komunitas UMKM.</p>
        </div>
    </header>

    <form method="POST" action="{{ route('dashboard.community.update', $post['id']) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="form-section">
            <label class="form-label">Kategori</label>
            <div class="category-grid">
                @foreach ($categories as $cat)
                    <label class="cat-option">
                        <input type="radio" name="category" value="{{ $cat }}" @checked(old('category', $post['category']) === $cat) required>
                        <span class="cat-card">{{ $cat }}</span>
                    </label>
                @endforeach
            </div>
            @error('category')<small class="form-error">{{ $message }}</small>@enderror
        </div>

        <div class="form-section">
            <label class="form-label">Judul</label>
            <input type="text" name="title" value="{{ old('title', $post['title']) }}" required maxlength="160" class="text-input" placeholder="Contoh: Cara Saya Meningkatkan Penjualan...">
            @error('title')<small class="form-error">{{ $message }}</small>@enderror
        </div>

        <div class="form-section">
            <label class="form-label">Isi Cerita</label>
            <textarea name="content" required minlength="25" class="text-input textarea-large" placeholder="Tulis cerita, pengalaman, atau tips Anda di sini...">{{ old('content', $post['content']) }}</textarea>
            <small class="form-help">Minimum 25 karakter.</small>
            @error('content')<small class="form-error">{{ $message }}</small>@enderror
        </div>

        <div class="form-section">
            <label class="form-label">Gambar Sampul (opsional)</label>
            <input id="imageInput" type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="text-input" data-image-input>
            <input type="hidden" name="image" data-image-hidden>
            <div class="image-preview" data-image-preview>
                @if (!empty($post['cover']) && str_starts_with($post['cover'], 'data:image'))
                    <img src="{{ $post['cover'] }}" alt="Cover">
                @endif
            </div>
            <small class="form-help">Kosongkan jika tidak ingin mengubah gambar. JPG/PNG/WebP, maksimal 2MB.</small>
            @error('image')<small class="form-error">{{ $message }}</small>@enderror
        </div>

        <div class="tips-box">
            <h4>Tips Menulis</h4>
            <ul>
                <li>Ceritakan dengan jujur dan detail</li>
                <li>Sertakan angka atau data jika ada</li>
                <li>Bagikan tips praktis yang bisa diterapkan</li>
                <li>Gunakan bahasa yang mudah dipahami</li>
            </ul>
        </div>

        <div class="edit-actions">
            <a href="{{ route('dashboard.community.show', $post['id']) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="submit-btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.flash-error { margin-bottom: 14px; padding: 12px 16px; border-radius: 12px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-weight: 600; }
.flash-error ul { margin: 0; padding-left: 18px; }

.create-header {
    display: flex;
    gap: 14px;
    align-items: center;
    background: #005A2B;
    color: #fff;
    padding: 22px 24px;
    border-bottom-left-radius: 28px;
    border-bottom-right-radius: 28px;
    margin: -24px -24px 22px;
}
@media (min-width: 1024px) { .create-header { margin: 0 0 22px; } }
.back-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,.15); color: #fff; text-decoration: none; flex-shrink: 0; }
.back-btn i { width: 18px; height: 18px; }
.back-btn:hover { background: rgba(255,255,255,.25); }
.create-header h2 { margin: 0 0 4px; font-size: 20px; color: #fff; }
.create-header p { margin: 0; font-size: 13px; color: rgba(255,255,255,.85); }

form { display: flex; flex-direction: column; gap: 22px; background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 18px rgba(15, 90, 52, .08); }
.form-section { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-weight: 700; color: #0f172a; font-size: 14px; }
.text-input { border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-family: inherit; color: #0f172a; background: #fff; }
.text-input:focus { outline: none; border-color: #005A2B; box-shadow: 0 0 0 3px rgba(0, 90, 43, .12); }
.textarea-large { min-height: 250px; max-height: 600px; resize: vertical; line-height: 1.6; }
.form-help { color: #94a3b8; font-size: 12px; }
.form-error { color: #dc2626; font-size: 12px; font-weight: 600; }

.category-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.cat-option input { display: none; }
.cat-card { display: flex; align-items: center; justify-content: center; text-align: center; padding: 14px 12px; border-radius: 12px; background: #F3F3F3; color: #334155; font-weight: 600; cursor: pointer; transition: all .15s ease; border: 2px solid transparent; }
.cat-option input:checked + .cat-card { background: #005A2B; color: #fff; border-color: #005A2B; }
.cat-option:hover .cat-card { border-color: #cbd5e1; }

.tips-box { background: #EAF7F0; border: 1px solid #c6e6d0; border-radius: 14px; padding: 16px 18px; }
.tips-box h4 { margin: 0 0 8px; color: #0f5a34; font-size: 14px; font-weight: 700; }
.tips-box ul { margin: 0; padding-left: 18px; color: #166534; font-size: 13px; line-height: 1.7; }

.image-preview { margin-top: 8px; }
.image-preview img { max-width: 320px; max-height: 200px; border-radius: 14px; border: 1px solid #dce5de; object-fit: cover; }

.edit-actions { display: flex; gap: 12px; }
.btn-cancel { flex: 1; height: 52px; border-radius: 12px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-weight: 700; text-decoration: none; }
.btn-cancel:hover { background: #e2e8f0; }
.submit-btn { flex: 2; height: 52px; border-radius: 12px; background: #005A2B; color: #fff; border: none; font-size: 15px; font-weight: 700; cursor: pointer; }
.submit-btn:hover { background: #004521; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();

    const input = document.querySelector('[data-image-input]');
    const hidden = document.querySelector('[data-image-hidden]');
    const preview = document.querySelector('[data-image-preview]');
    if (input && hidden && preview) {
        input.addEventListener('change', function () {
            const f = input.files && input.files[0];
            preview.innerHTML = '';
            hidden.value = '';
            if (!f) return;
            if (f.size > 2 * 1024 * 1024) { alert('Ukuran gambar maksimal 2MB.'); input.value = ''; return; }
            const r = new FileReader();
            r.onload = e => {
                hidden.value = e.target.result;
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            r.readAsDataURL(f);
        });
    }
});
</script>
@endpush
