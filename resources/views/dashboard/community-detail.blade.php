@extends('layouts.dashboard')

@section('page-title', 'Detail Artikel')

@section('dashboard-content')
<div class="article-detail-shell">
    <div class="article-detail-hero">
        <a href="{{ route('dashboard.community') }}" class="detail-back">←</a>
        <span class="detail-category">{{ $post['category'] }}</span>
        <h2>{{ $post['title'] }}</h2>
        <div class="detail-meta">
            <div>
                <div class="detail-author">{{ $post['author'] }}</div>
                <div class="detail-role">{{ $post['author_role'] }}</div>
            </div>
            <div class="detail-date">{{ $post['date'] }}</div>
        </div>
    </div>

    <div class="detail-card">
        <p class="detail-excerpt">{{ $post['content'] }}</p>

        <div class="reaction-bar">
            <button class="reaction-btn like-toggle" type="button" data-like-button aria-pressed="false">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg>
                <span>{{ $post['likes'] }}</span>
            </button>
            <a href="#comments" class="reaction-btn">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 6h-18c-1.1 0-2 .9-2 2v12l4-4h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 7H7v-2h10v2zm3-4H7V7h13v2z" fill="currentColor"/></svg>
                <span>{{ $post['comments'] }}</span>
            </a>
            <button class="reaction-btn" type="button">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 16.08c-.76 0-1.44.3-1.96.78l-7.12-4.16c.05-.23.08-.46.08-.7s-.03-.47-.08-.7L16.04 7.2A2.99 2.99 0 0 0 21 5c0-1.66-1.34-3-3-3s-3 1.34-3 3c0 .24.03.47.08.7L8.96 9.86A2.99 2.99 0 0 0 4 12c0 1.66 1.34 3 3 3 .76 0 1.44-.3 1.96-.78l7.12 4.16c-.05.23-.08.46-.08.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/></svg>
                <span>Bagikan</span>
            </button>
        </div>

        <div class="detail-body">
            <p>{{ $post['content'] }}</p>
            <p>Artikel ini dibuat untuk memberi gambaran yang lebih jelas kepada komunitas mengenai pengalaman dan strategi yang bisa langsung diterapkan.</p>
        </div>

        <div class="comments-card" id="comments">
            <h3>Komentar ({{ $post['comments'] }})</h3>

            @foreach($comments as $comment)
            <div class="comment-item">
                <div class="comment-head">
                    <strong>{{ $comment['author'] }}</strong>
                    <span>{{ $comment['time'] }}</span>
                </div>
                <p>{{ $comment['text'] }}</p>
            </div>
            @endforeach

            <textarea class="comment-input" rows="4" placeholder="Tulis komentar..."></textarea>
            <button type="button" class="comment-submit">Kirim Komentar</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.article-detail-shell {
    max-width: 860px;
    margin: 0 auto;
}
.article-detail-hero {
    padding: 24px;
    border-radius: 24px;
    background: linear-gradient(135deg, #0f5a34 0%, #0a4326 100%);
    color: #fff;
    box-shadow: var(--shadow);
}
.detail-back {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255,255,255,.14);
    color: #fff;
    text-decoration: none;
    margin-bottom: 14px;
    font-size: 18px;
}
.detail-category {
    display: inline-flex;
    align-items: center;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.15);
    font-size: 12px;
    font-weight: 700;
}
.article-detail-hero h2 {
    margin: 14px 0 16px;
    font-size: 32px;
    line-height: 1.18;
}
.detail-meta {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: end;
    flex-wrap: wrap;
}
.detail-author {
    font-size: 16px;
    font-weight: 700;
}
.detail-role,
.detail-date {
    font-size: 13px;
    color: rgba(255,255,255,.8);
}
.detail-card {
    margin-top: 18px;
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 12px 32px rgba(15, 90, 52, .08);
}
.detail-excerpt,
.detail-body p {
    color: #43584f;
    line-height: 1.8;
    font-size: 15px;
}
.reaction-bar {
    display: flex;
    gap: 12px;
    margin: 20px 0 24px;
    flex-wrap: wrap;
}
.reaction-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 999px;
    border: 1px solid #dde6e0;
    background: #f6f8f7;
    color: #465d53;
    text-decoration: none;
    font-weight: 700;
    transition: all .2s ease;
}
.reaction-btn:hover {
    background: #eaf4ee;
    color: #0f5a34;
}
.like-toggle.is-active {
    background: #e4f0ff;
    color: #1d68d4;
    border-color: #c9defb;
}
.reaction-icon {
    width: 16px;
    height: 16px;
}
.detail-body {
    padding-top: 4px;
}
.comments-card {
    margin-top: 26px;
    padding: 20px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #edf1ee;
    box-shadow: 0 10px 24px rgba(15, 90, 52, .06);
}
.comments-card h3 {
    margin: 0 0 18px;
    font-size: 18px;
}
.comment-item {
    padding: 14px 0;
    border-bottom: 1px solid #eff3ef;
}
.comment-item:last-of-type {
    border-bottom: none;
}
.comment-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #607468;
    margin-bottom: 8px;
    font-size: 13px;
}
.comment-item p {
    margin: 0;
    color: #44574d;
    line-height: 1.7;
}
.comment-input {
    width: 100%;
    margin-top: 18px;
    border: 1px solid #cfd8d1;
    border-radius: 16px;
    padding: 16px;
    resize: vertical;
}
.comment-submit {
    margin-top: 14px;
    border: none;
    border-radius: 999px;
    padding: 12px 18px;
    background: #0f5a34;
    color: #fff;
    font-weight: 700;
}
@media (max-width: 768px) {
    .article-detail-hero,
    .detail-card,
    .comments-card {
        padding: 18px;
        border-radius: 18px;
    }
    .article-detail-hero h2 {
        font-size: 24px;
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