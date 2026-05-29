@extends('layouts.dashboard')

@section('page-title', 'Bagikan Cerita Anda')

@section('dashboard-content')
<div class="article-create-shell">
    <div class="create-header">
        <a href="{{ route('dashboard.community') }}" class="back-link">←</a>
        <div>
            <p class="page-kicker">Blog & Artikel</p>
            <h2>Bagikan Cerita Anda</h2>
        </div>
    </div>

    <div class="create-card">
        <p class="create-intro">Berbagi pengalaman Anda dapat membantu dan menginspirasi pengusaha UMKM lainnya.</p>

        <div class="form-group">
            <label>Kategori <span>*</span></label>
            <div class="category-grid">
                @foreach($categories as $category)
                <button type="button" class="category-pill {{ $loop->first ? 'active' : '' }}">{{ $category }}</button>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Judul <span>*</span></label>
            <input type="text" class="input-field" placeholder="Contoh: Cara Saya Meningkatkan Penjualan...">
        </div>

        <div class="form-group">
            <label>Cerita Anda <span>*</span></label>
            <textarea class="textarea-field" rows="10" placeholder="Tulis cerita, pengalaman, atau tips Anda di sini..."></textarea>
            <small>Minimum 100 karakter</small>
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

        <button type="button" class="submit-article">✈ Bagikan Cerita</button>
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
}
.textarea-field {
    resize: vertical;
    min-height: 220px;
}
.form-group small {
    display: block;
    margin-top: 8px;
    color: #6e7e76;
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
.submit-article {
    width: 100%;
    min-height: 58px;
    border: none;
    border-radius: 18px;
    background: #8da99a;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
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
}
</style>
@endpush