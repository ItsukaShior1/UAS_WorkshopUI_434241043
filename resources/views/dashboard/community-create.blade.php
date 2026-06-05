@extends('layouts.dashboard')

@section('page-title', 'Bagikan Cerita Anda')

@section('dashboard-content')
<div class="article-create-shell">
    <div class="create-header">
        <a href="{{ route('dashboard.community') }}" class="back-link" aria-label="Kembali">←</a>
        <div>
            <p class="page-kicker">Blog & Artikel</p>
            <h2>Bagikan Cerita Anda</h2>
        </div>
    </div>

    <div class="create-card">
        <p class="create-intro">Berbagi pengalaman Anda dapat membantu dan menginspirasi pengusaha UMKM lainnya.</p>

        <form method="POST" action="{{ route('dashboard.community.store') }}" novalidate>
            @csrf

            <div class="form-group">
                <label>Kategori <span>*</span></label>
                <div class="category-grid" data-category-picker>
                    <input type="hidden" name="category" id="categoryInput" value="{{ old('category', $categories[0]) }}">
                    @foreach($categories as $category)
                        <button type="button"
                                class="category-pill {{ old('category', $categories[0]) === $category ? 'active' : '' }}"
                                data-category-value="{{ $category }}">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
                @error('category')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="titleInput">Judul <span>*</span></label>
                <input id="titleInput" type="text" name="title" maxlength="160"
                       class="input-field @error('title') is-invalid @enderror"
                       placeholder="Contoh: Cara Saya Meningkatkan Penjualan..."
                       value="{{ old('title') }}">
                @error('title')
                    <small class="form-error">{{ $message }}</small>
                @else
                    <small>Maksimal 160 karakter</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="contentInput">Cerita Anda <span>*</span></label>
                <textarea id="contentInput" name="content" rows="10" maxlength="5000"
                          class="textarea-field @error('content') is-invalid @enderror"
                          placeholder="Tulis cerita, pengalaman, atau tips Anda di sini..."
                          data-content-counter>{{ old('content') }}</textarea>
                <div class="content-meta">
                    <small>Minimum 100 karakter</small>
                    <small><span data-content-count>{{ strlen(old('content', '')) }}</span>/5000</small>
                </div>
                @error('content')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="tips-box">
                <h4>💡 Tips Menulis</h4>
                <ul>
                    <li>Ceritakan dengan jujur dan detail</li>
                    <li>Sertakan angka atau data jika ada</li>
                    <li>Bagikan tips praktis yang bisa diterapkan</li>
                    <li>Gunakan bahasa yang mudah dipahami</li>
                </ul>
            </div>

            <div class="create-actions">
                <a href="{{ route('dashboard.community') }}" class="btn-secondary-action">Batal</a>
                <button type="submit" class="submit-article">✈ Bagikan Cerita</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.article-create-shell {
    max-width: 860px;
    margin: 0 auto;
}
.create-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
}
.back-link {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f2f6f3;
    color: #0f5a34;
    text-decoration: none;
    font-size: 20px;
    font-weight: 700;
    transition: all .2s ease;
}
.back-link:hover {
    background: #0f5a34;
    color: #fff;
}
.page-kicker {
    margin: 0 0 4px;
    color: #5d7d6f;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
}
.create-header h2 {
    margin: 0;
    color: #123122;
    font-size: 28px;
}
.create-card {
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 12px 32px rgba(15, 90, 52, .08);
}
.create-intro {
    margin: 0 0 22px;
    color: #54685f;
    line-height: 1.6;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #12291d;
}
.form-group label span {
    color: #d64545;
}
.category-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
.category-pill {
    min-height: 52px;
    border: 1px solid #dce5de;
    border-radius: 16px;
    background: #f5f7f5;
    color: #31453b;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}
.category-pill:hover {
    background: #eaf4ee;
    border-color: #cfe1d6;
}
.category-pill.active {
    background: #0f5a34;
    color: #fff;
    border-color: #0f5a34;
}
.input-field,
.textarea-field {
    width: 100%;
    border: 1px solid #cfd8d1;
    border-radius: 16px;
    padding: 16px 18px;
    font-size: 15px;
    background: #fff;
    font-family: inherit;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.input-field:focus,
.textarea-field:focus {
    outline: none;
    border-color: #0f5a34;
    box-shadow: 0 0 0 3px rgba(15, 90, 52, .12);
}
.is-invalid {
    border-color: #d64545 !important;
    box-shadow: 0 0 0 3px rgba(214, 69, 69, .12) !important;
}
.textarea-field {
    resize: vertical;
    min-height: 220px;
}
.content-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
    color: #6e7e76;
    font-size: 12px;
}
.form-group small {
    display: block;
    margin-top: 8px;
    color: #6e7e76;
}
.form-error {
    color: #d64545 !important;
    font-weight: 600;
}
.tips-box {
    background: #eaf9f0;
    border-left: 4px solid #38b26d;
    border-radius: 16px;
    padding: 18px 18px 16px;
    margin-bottom: 20px;
}
.tips-box h4 {
    margin: 0 0 10px;
    color: #0f5a34;
}
.tips-box ul {
    margin: 0;
    padding-left: 18px;
    color: #385246;
}
.tips-box li {
    margin-bottom: 8px;
}
.create-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
}
.btn-secondary-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 22px;
    border-radius: 18px;
    background: #f2f6f3;
    color: #31453b;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
}
.btn-secondary-action:hover {
    background: #e6ede8;
}
.submit-article {
    border: none;
    border-radius: 18px;
    padding: 14px 26px;
    background: #0f5a34;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}
.submit-article:hover {
    background: #0a4326;
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(15, 90, 52, .25);
}
@media (max-width: 768px) {
    .create-card {
        padding: 18px;
        border-radius: 18px;
    }
    .category-grid {
        grid-template-columns: 1fr;
    }
    .create-header h2 {
        font-size: 22px;
    }
    .create-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }
    .btn-secondary-action,
    .submit-article {
        width: 100%;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.querySelector('[data-category-picker]');
    const input = document.getElementById('categoryInput');
    if (picker && input) {
        picker.addEventListener('click', function (event) {
            const pill = event.target.closest('[data-category-value]');
            if (!pill) return;
            picker.querySelectorAll('.category-pill').forEach(function (el) {
                el.classList.remove('active');
            });
            pill.classList.add('active');
            input.value = pill.dataset.categoryValue;
        });
    }

    const counter = document.querySelector('[data-content-counter]');
    const countTarget = document.querySelector('[data-content-count]');
    if (counter && countTarget) {
        const update = () => { countTarget.textContent = counter.value.length; };
        counter.addEventListener('input', update);
        update();
    }
});
</script>
@endpush
