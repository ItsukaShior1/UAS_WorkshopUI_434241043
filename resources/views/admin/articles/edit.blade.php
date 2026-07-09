@extends('admin.layout')

@section('page-title', 'Edit Artikel Komunitas')

@section('admin-content')
<div class="admin-form-page">
    <div class="admin-page-header">
        <div>
            <a href="{{ route('admin.articles.index') }}" class="btn-admin-text">Kembali</a>
            <h2>Edit Artikel Komunitas</h2>
            <p>Kelola artikel komunitas yang tampil di halaman user.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="admin-form-card">
        @csrf
        @method('PUT')
        <div class="form-row">
            <label>Kategori <span class="req">*</span></label>
            <select name="category" required class="admin-input">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $article->category) === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <label>Judul <span class="req">*</span></label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" required maxlength="180" class="admin-input">
        </div>
        <div class="form-row">
            <label>Ringkasan (Excerpt)</label>
            <textarea name="excerpt" rows="3" maxlength="500" class="admin-input">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
        <div class="form-row">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="admin-input" data-thumb-input>
            <input type="hidden" name="thumbnail" data-thumb-hidden>
            <div class="thumb-preview" data-thumb-preview>
                @if ($article->image_data)
                    <img src="{{ $article->image_data_uri }}" alt="Thumbnail saat ini">
                @endif
            </div>
            <small class="form-help">Kosongkan jika tidak ingin mengubah thumbnail.</small>
        </div>
        <div class="form-row">
            <label>Isi Artikel <span class="req">*</span></label>
            <textarea name="content" rows="12" required minlength="25" class="admin-input admin-textarea-large">{{ old('content', $article->content) }}</textarea>
        </div>
        <div class="form-row">
            <label class="switch">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published))>
                <span class="switch-slider"></span>
                <span class="switch-label">Publikasikan</span>
            </label>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.articles.index') }}" class="btn-admin-text">Batal</a>
            <button type="submit" class="btn-admin-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.admin-form-page { display: flex; flex-direction: column; gap: 20px; max-width: 820px; }
.admin-page-header h2 { margin: 4px 0 4px; color: #0f172a; }
.admin-page-header p { margin: 0; color: #64748b; font-size: 14px; }
.admin-form-card { background: #fff; border-radius: 18px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,.04); display: flex; flex-direction: column; gap: 16px; }
.form-row { display: flex; flex-direction: column; gap: 8px; }
.form-row label { font-weight: 600; color: #1e293b; font-size: 14px; }
.req { color: #dc2626; }
.form-help { color: #94a3b8; font-size: 12px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-family: inherit; background: #fff; }
.admin-input:focus { outline: none; border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,.08); }
.admin-textarea-large { min-height: 280px; resize: vertical; line-height: 1.55; }
.switch { display: inline-flex; align-items: center; gap: 12px; cursor: pointer; }
.switch input { display: none; }
.switch-slider { width: 44px; height: 24px; background: #cbd5e1; border-radius: 999px; position: relative; transition: .2s; }
.switch-slider::after { content: ""; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: #fff; border-radius: 50%; transition: .2s; }
.switch input:checked + .switch-slider { background: #16a34a; }
.switch input:checked + .switch-slider::after { transform: translateX(20px); }
.switch-label { color: #1e293b; font-size: 14px; font-weight: 600; }
.thumb-preview { margin-top: 6px; }
.thumb-preview img { max-width: 240px; max-height: 160px; border-radius: 12px; border: 1px solid #e2e8f0; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; align-items: center; padding-top: 8px; border-top: 1px solid #f1f5f9; margin-top: 8px; }
.btn-admin-primary { background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
.btn-admin-primary:hover { background: #1e293b; }
.btn-admin-text { color: #64748b; text-decoration: none; padding: 12px 14px; font-weight: 600; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-alert ul { margin: 0; padding-left: 18px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('[data-thumb-input]');
    const hidden = document.querySelector('[data-thumb-hidden]');
    const preview = document.querySelector('[data-thumb-preview]');
    if (!input || !hidden || !preview) return;

    input.addEventListener('change', function () {
        const file = input.files && input.files[0];
        preview.innerHTML = '';
        hidden.value = '';
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            const dataUrl = e.target.result;
            hidden.value = dataUrl;
            const img = document.createElement('img');
            img.src = dataUrl;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
