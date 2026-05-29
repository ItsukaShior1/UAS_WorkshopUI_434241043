@extends('layouts.dashboard')

@section('page-title', 'Komunitas')

@section('dashboard-content')
<div id="communityContent" class="page-content active">
    <div class="community-hero">
        <div>
            <p class="section-kicker">Blog & Artikel</p>
            <h2>Bagikan cerita, inspirasi, dan tips bisnis</h2>
            <p class="hero-copy">Tuliskan pengalamanmu dan baca artikel komunitas lain yang relevan untuk UMKM.</p>
        </div>
        <a href="{{ route('dashboard.community.create') }}" class="hero-action">+ Tulis Artikel</a>
    </div>

    <div class="community-toolbar">
        <div class="community-search">
            <input type="text" placeholder="Cari artikel atau topik..." class="search-input">
        </div>

        <div class="community-tabs">
            <button class="tab-btn active" type="button">Terbaru</button>
            <button class="tab-btn" type="button">Populer</button>
            <button class="tab-btn" type="button">Terdekat</button>
        </div>
    </div>

    <div class="posts-list">
        @foreach($posts as $post)
        <article class="post-card">
            <a href="{{ route('dashboard.community.show', $post['id']) }}" class="post-link">
                <div class="post-cover" style="background-image: url('{{ $post['cover'] }}')">
                    <span class="post-badge">{{ $post['category'] }}</span>
                </div>

                <div class="post-body">
                    <div class="post-header">
                        <div class="post-author">
                            <img src="{{ $post['avatar'] }}" alt="{{ $post['author'] }}" class="author-avatar">
                            <div>
                                <h4>{{ $post['author'] }}</h4>
                                <p class="post-category">{{ $post['author_role'] }}</p>
                            </div>
                        </div>
                        <span class="post-meta">{{ $post['read_time'] }}</span>
                    </div>

                    <div class="post-content">
                        <h3>{{ $post['title'] }}</h3>
                        <p>{{ $post['excerpt'] }}</p>
                    </div>
                </div>
            </a>

            <div class="post-footer">
                <button class="post-action like-toggle" type="button" aria-pressed="false" data-like-button>
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                    </svg>
                    <span>{{ $post['likes'] }}</span>
                </button>
                <a class="post-action" href="{{ route('dashboard.community.show', $post['id']) }}#comments">
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M21 6h-18c-1.1 0-2 .9-2 2v12l4-4h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 7H7v-2h10v2zm3-4H7V7h13v2z" fill="currentColor"/>
                    </svg>
                    <span>{{ $post['comments'] }}</span>
                </a>
                <button class="post-action" type="button">
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M18 16.08c-.76 0-1.44.3-1.96.78l-7.12-4.16c.05-.23.08-.46.08-.7s-.03-.47-.08-.7L16.04 7.2A2.99 2.99 0 0 0 21 5c0-1.66-1.34-3-3-3s-3 1.34-3 3c0 .24.03.47.08.7L8.96 9.86A2.99 2.99 0 0 0 4 12c0 1.66 1.34 3 3 3 .76 0 1.44-.3 1.96-.78l7.12 4.16c-.05.23-.08.46-.08.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                    </svg>
                    <span>Bagikan</span>
                </button>
            </div>
        </article>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
.community-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 24px;
    border-radius: 20px;
    background: linear-gradient(135deg, #0f5a34 0%, #0a4326 100%);
    color: #fff;
    box-shadow: var(--shadow);
}
.section-kicker {
    margin: 0 0 8px;
    font-size: 12px;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .78;
}
.community-hero h2 {
    margin: 0;
    font-size: 28px;
    line-height: 1.2;
}
.hero-copy {
    margin: 10px 0 0;
    max-width: 640px;
    color: rgba(255,255,255,.84);
}
.hero-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 18px;
    border-radius: 14px;
    background: #e8f5ee;
    color: #0f5a34;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}
.community-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin: 18px 0 22px;
}
.community-search {
    flex: 1;
}
.search-input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #d9e2dc;
    border-radius: 14px;
    font-size: 14px;
    background: #fff;
}
.community-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.tab-btn {
    padding: 10px 16px;
    border: 1px solid #d9e2dc;
    background: #fff;
    border-radius: 999px;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    transition: all .2s ease;
}
.tab-btn.active {
    background: #0f5a34;
    color: #fff;
    border-color: #0f5a34;
}
.posts-list {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}
.post-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e8ece8;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(15, 90, 52, .06);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 34px rgba(15, 90, 52, .12);
    border-color: #cfe1d6;
}
.post-link {
    display: block;
    color: inherit;
    text-decoration: none;
}
.post-cover {
    min-height: 180px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    background-size: cover;
    background-position: center;
    position: relative;
}
.post-cover::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,.08), rgba(0,0,0,.4));
}
.post-badge {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.92);
    color: #0f5a34;
    font-size: 12px;
    font-weight: 700;
}
.post-body {
    padding: 16px 16px 0;
}
.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.post-author {
    display: flex;
    gap: 12px;
    align-items: center;
}
.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 6px 16px rgba(0,0,0,.12);
}
.post-author h4 {
    margin: 0;
    font-size: 14px;
    color: #11261b;
}
.post-category {
    margin: 2px 0 0 0;
    font-size: 12px;
    color: #628174;
}
.post-meta {
    font-size: 12px;
    font-weight: 600;
    color: #628174;
}
.post-content {
    padding-bottom: 18px;
}
.post-content h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    line-height: 1.35;
    font-weight: 800;
    color: #123122;
}
.post-content p {
    margin: 0;
    font-size: 14px;
    color: #4a5d54;
    line-height: 1.65;
}
.post-footer {
    display: flex;
    gap: 10px;
    border-top: 1px solid #eef3ef;
    padding: 14px 16px 16px;
    align-items: center;
    flex-wrap: wrap;
}
.post-action {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f4f7f5;
    border: 1px solid transparent;
    border-radius: 999px;
    padding: 10px 14px;
    cursor: pointer;
    color: #4a5d54;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s ease;
}
.post-action:hover {
    background: #eaf4ee;
    color: #0f5a34;
    border-color: #cfe1d6;
}
.like-toggle.is-active {
    background: #e4f0ff;
    color: #1d68d4;
    border-color: #c9defb;
}
.like-toggle.is-active:hover {
    background: #d8eaff;
    color: #1d68d4;
}
.action-icon {
    width: 16px;
    height: 16px;
    flex: 0 0 16px;
}
@media (max-width: 768px) {
    .community-hero,
    .community-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .posts-list {
        grid-template-columns: 1fr;
    }
    .community-hero h2 {
        font-size: 22px;
    }
    .post-content h3 {
        font-size: 16px;
    }
    .post-footer {
        padding: 14px;
    }
}
</style>
<script>
document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-like-button]');

    if (!button) {
        return;
    }

    button.classList.toggle('is-active');
    button.setAttribute('aria-pressed', button.classList.contains('is-active') ? 'true' : 'false');
});
</script>
@endpush
