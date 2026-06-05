@extends('layouts.dashboard')

@section('page-title', 'Detail Artikel')

@section('dashboard-content')
@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

<div class="article-detail-shell">
    <div class="article-detail-hero">
        <a href="{{ route('dashboard.community') }}" class="detail-back" aria-label="Kembali">←</a>
        <span class="detail-category">{{ $post['category'] }}</span>
        <h2>{{ $post['title'] }}</h2>
        <div class="detail-meta">
            <div class="detail-author-block">
                <img src="{{ $post['avatar'] }}" alt="{{ $post['author'] }}" class="detail-avatar"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post['author']) }}&background=0f5a34&color=fff'">
                <div>
                    <div class="detail-author">{{ $post['author'] }}</div>
                    <div class="detail-role">{{ $post['author_role'] }}</div>
                </div>
            </div>
            <div class="detail-date">{{ $post['date'] }} • {{ $post['read_time'] }}</div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-body">
            @foreach(preg_split("/\n\s*\n/", $post['content']) as $paragraph)
                @if(trim($paragraph) !== '')
                    <p>{{ $paragraph }}</p>
                @endif
            @endforeach
        </div>

        <div class="reaction-bar">
            <button class="reaction-btn like-toggle" type="button"
                    data-like-button
                    data-like-url="{{ route('dashboard.community.like', $post['id']) }}">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                </svg>
                <span data-like-count>{{ $post['likes'] }}</span>
                <span class="reaction-label">Suka</span>
            </button>
            <a href="#comments" class="reaction-btn">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 6h-18c-1.1 0-2 .9-2 2v12l4-4h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 7H7v-2h10v2zm3-4H7V7h13v2z" fill="currentColor"/>
                </svg>
                <span>{{ $post['comments'] }}</span>
                <span class="reaction-label">Komentar</span>
            </a>
            <button class="reaction-btn" type="button"
                    data-share-button
                    data-share-url="{{ route('dashboard.community.show', $post['id'], false) }}"
                    data-share-title="{{ e($post['title']) }}"
                    data-share-text="{{ e($post['excerpt']) }}">
                <svg class="reaction-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 16.08c-.76 0-1.44.3-1.96.78l-7.12-4.16c.05-.23.08-.46.08-.7s-.03-.47-.08-.7L16.04 7.2A2.99 2.99 0 0 0 21 5c0-1.66-1.34-3-3-3s-3 1.34-3 3c0 .24.03.47.08.7L8.96 9.86A2.99 2.99 0 0 0 4 12c0 1.66 1.34 3 3 3 .76 0 1.44-.3 1.96-.78l7.12 4.16c-.05.23-.08.46-.08.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                </svg>
                <span>Bagikan</span>
            </button>
        </div>

        <div class="comments-card" id="comments">
            <div class="comments-head">
                <h3>Komentar ({{ $post['comments'] }})</h3>
            </div>

            @forelse($comments as $comment)
            <div class="comment-item">
                <div class="comment-head">
                    <div class="comment-author-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment['author']) }}&background=0f5a34&color=fff" alt="{{ $comment['author'] }}" class="comment-avatar">
                        <div>
                            <strong>{{ $comment['author'] }}</strong>
                            <span class="comment-time">{{ $comment['time'] }}</span>
                        </div>
                    </div>
                </div>
                <p>{{ $comment['text'] }}</p>
            </div>
            @empty
            <div class="comment-empty">Belum ada komentar. Jadi yang pertama berkomentar!</div>
            @endforelse

            <form method="POST" action="{{ route('dashboard.community.comment.store', $post['id']) }}" class="comment-form" novalidate>
                @csrf
                <div class="form-group">
                    <label for="commentBody">Tulis Komentar</label>
                    <textarea id="commentBody" name="body" rows="4" maxlength="500"
                              class="comment-input @error('body') is-invalid @enderror"
                              placeholder="Bagikan pendapatmu...">{{ old('body') }}</textarea>
                    @error('body')
                        <small class="form-error">{{ $message }}</small>
                    @else
                        <small>Maksimal 500 karakter</small>
                    @enderror
                </div>
                <div class="comment-actions">
                    <a href="{{ route('dashboard.community') }}" class="btn-secondary-action">Kembali</a>
                    <button type="submit" class="comment-submit">Kirim Komentar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Share Modal (reused) --}}
<div class="share-modal" id="shareModal" hidden aria-hidden="true" role="dialog" aria-labelledby="shareModalTitle">
    <div class="share-backdrop" data-share-close></div>
    <div class="share-card" role="document">
        <button class="share-close" type="button" data-share-close aria-label="Tutup">×</button>
        <p class="share-kicker">Bagikan Artikel</p>
        <h3 id="shareModalTitle">—</h3>
        <div class="share-grid">
            <button type="button" class="share-option" data-share-target="whatsapp"><span class="share-icon" style="background:#25D366;">💬</span><span class="share-label">WhatsApp</span></button>
            <button type="button" class="share-option" data-share-target="facebook"><span class="share-icon" style="background:#1877F2;">f</span><span class="share-label">Facebook</span></button>
            <button type="button" class="share-option" data-share-target="twitter"><span class="share-icon" style="background:#000000;">𝕏</span><span class="share-label">Twitter / X</span></button>
            <button type="button" class="share-option" data-share-target="telegram"><span class="share-icon" style="background:#229ED9;">✈</span><span class="share-label">Telegram</span></button>
            <button type="button" class="share-option" data-share-target="email"><span class="share-icon" style="background:#EA4335;">✉</span><span class="share-label">Email</span></button>
            <button type="button" class="share-option" data-share-target="copy"><span class="share-icon" style="background:#0f5a34;">🔗</span><span class="share-label">Salin Tautan</span></button>
        </div>
        <div class="share-link-row">
            <input type="text" id="shareLinkInput" readonly value="">
            <button type="button" id="shareCopyBtn">Salin</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.flash-success {
    margin-bottom: 14px;
    padding: 12px 16px;
    border-radius: 14px;
    background: #e6f6ec;
    color: #0f5a34;
    border: 1px solid #b9e3c8;
    font-weight: 600;
}
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
    transition: all .2s ease;
}
.detail-back:hover {
    background: rgba(255,255,255,.25);
    transform: translateX(-2px);
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
    margin: 14px 0 18px;
    font-size: 32px;
    line-height: 1.18;
}
.detail-meta {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}
.detail-author-block {
    display: flex;
    gap: 12px;
    align-items: center;
}
.detail-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 6px 16px rgba(0,0,0,.18);
}
.detail-author {
    font-size: 16px;
    font-weight: 700;
}
.detail-role,
.detail-date {
    font-size: 13px;
    color: rgba(255,255,255,.82);
}
.detail-card {
    margin-top: 18px;
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 12px 32px rgba(15, 90, 52, .08);
}
.detail-body p {
    color: #43584f;
    line-height: 1.85;
    font-size: 15px;
    margin: 0 0 14px;
}
.detail-body p:last-child { margin-bottom: 0; }
.reaction-bar {
    display: flex;
    gap: 10px;
    margin: 22px 0 8px;
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
    cursor: pointer;
    transition: all .2s ease;
    font-family: inherit;
    font-size: 13px;
}
.reaction-btn:hover {
    background: #eaf4ee;
    color: #0f5a34;
    border-color: #cfe1d6;
}
.like-toggle.is-active {
    background: #e4f0ff;
    color: #1d68d4;
    border-color: #c9defb;
}
.reaction-label {
    font-size: 12px;
    opacity: .85;
}
.reaction-icon {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
.comments-card {
    margin-top: 26px;
    padding: 22px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #edf1ee;
    box-shadow: 0 10px 24px rgba(15, 90, 52, .06);
}
.comments-head {
    margin-bottom: 18px;
}
.comments-card h3 {
    margin: 0;
    font-size: 18px;
}
.comment-item {
    padding: 16px 0;
    border-bottom: 1px solid #eff3ef;
}
.comment-item:last-of-type {
    border-bottom: none;
}
.comment-head {
    margin-bottom: 8px;
    color: #607468;
    font-size: 13px;
}
.comment-author-block {
    display: flex;
    gap: 10px;
    align-items: center;
}
.comment-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.comment-author-block strong {
    display: block;
    color: #123122;
    font-size: 14px;
}
.comment-time {
    color: #6e7e76;
    font-size: 12px;
    font-weight: 500;
}
.comment-item p {
    margin: 0;
    padding-left: 46px;
    color: #44574d;
    line-height: 1.7;
}
.comment-empty {
    padding: 18px;
    text-align: center;
    color: #6e7e76;
    background: #f6f8f7;
    border-radius: 12px;
    margin-bottom: 16px;
}
.comment-form {
    margin-top: 18px;
    border-top: 1px solid #eff3ef;
    padding-top: 18px;
}
.comment-form .form-group {
    margin-bottom: 12px;
}
.comment-form label {
    display: block;
    font-weight: 700;
    color: #12291d;
    margin-bottom: 8px;
}
.comment-input {
    width: 100%;
    border: 1px solid #cfd8d1;
    border-radius: 16px;
    padding: 16px;
    resize: vertical;
    font-family: inherit;
    font-size: 14px;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.comment-input:focus {
    outline: none;
    border-color: #0f5a34;
    box-shadow: 0 0 0 3px rgba(15, 90, 52, .12);
}
.is-invalid {
    border-color: #d64545 !important;
    box-shadow: 0 0 0 3px rgba(214, 69, 69, .12) !important;
}
.form-error {
    color: #d64545 !important;
    font-weight: 600;
    margin-top: 6px;
    display: block;
}
.comment-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-secondary-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 18px;
    border-radius: 999px;
    background: #f2f6f3;
    color: #31453b;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
}
.btn-secondary-action:hover { background: #e6ede8; }
.comment-submit {
    border: none;
    border-radius: 999px;
    padding: 12px 22px;
    background: #0f5a34;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}
.comment-submit:hover {
    background: #0a4326;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(15, 90, 52, .25);
}

/* Share Modal (reused) */
.share-modal[hidden] {
    display: none !important;
}
.share-modal {
    position: fixed;
    inset: 0;
    z-index: 80;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.share-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 25, 20, .55);
    backdrop-filter: blur(2px);
}
.share-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 24px;
    padding: 26px 24px 22px;
    box-shadow: 0 24px 60px rgba(15, 90, 52, .25);
    animation: share-pop .25s ease;
}
@keyframes share-pop {
    from { transform: scale(.96); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.share-close {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #f2f6f3;
    color: #31453b;
    font-size: 22px;
    cursor: pointer;
    line-height: 1;
}
.share-kicker {
    margin: 0;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #0f5a34;
}
.share-card h3 {
    margin: 6px 0 18px;
    color: #123122;
    font-size: 20px;
    line-height: 1.3;
    word-break: break-word;
}
.share-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}
.share-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 14px 8px;
    border: 1px solid #e8ece8;
    border-radius: 16px;
    background: #fafcfa;
    cursor: pointer;
    transition: all .2s ease;
    font-family: inherit;
}
.share-option:hover {
    background: #eaf4ee;
    border-color: #cfe1d6;
    transform: translateY(-1px);
}
.share-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    font-weight: 800;
}
.share-label {
    font-size: 12px;
    font-weight: 700;
    color: #31453b;
}
.share-link-row {
    display: flex;
    gap: 8px;
    align-items: stretch;
}
.share-link-row input {
    flex: 1;
    border: 1px solid #d9e2dc;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 12px;
    background: #f6f8f7;
    color: #31453b;
    min-width: 0;
}
#shareCopyBtn {
    border: none;
    border-radius: 12px;
    padding: 0 16px;
    background: #0f5a34;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s ease;
}
#shareCopyBtn:hover { background: #0a4326; }
#shareCopyBtn.is-copied { background: #1d68d4; }

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
    .comment-item p { padding-left: 0; }
    .comment-actions { flex-direction: column-reverse; align-items: stretch; }
    .btn-secondary-action, .comment-submit { width: 100%; }
}
</style>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Like
    document.addEventListener('click', async function (event) {
        const btn = event.target.closest('[data-like-button]');
        if (!btn) return;
        event.preventDefault();
        const url = btn.dataset.likeUrl;
        const countEl = btn.querySelector('[data-like-count]');
        btn.classList.toggle('is-active');
        btn.setAttribute('aria-pressed', btn.classList.contains('is-active') ? 'true' : 'false');
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
            if (res.ok && countEl) {
                const data = await res.json();
                countEl.textContent = data.likes;
            }
        } catch (err) {
            console.warn('Like failed', err);
        }
    });

    // Share Modal
    const modal = document.getElementById('shareModal');
    const titleEl = document.getElementById('shareModalTitle');
    const linkInput = document.getElementById('shareLinkInput');
    const copyBtn = document.getElementById('shareCopyBtn');
    let currentUrl = '';
    let currentTitle = '';
    let currentText = '';

    function openShare(data) {
        currentUrl = data.url;
        currentTitle = data.title;
        currentText = data.text;
        titleEl.textContent = data.title;
        linkInput.value = data.url;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeShare() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    modal.addEventListener('click', function (e) {
        if (e.target.closest('[data-share-close]')) closeShare();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) closeShare();
    });
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-share-button]');
        if (!btn) return;
        e.preventDefault();
        openShare({
            url: btn.dataset.shareUrl,
            title: btn.dataset.shareTitle,
            text: btn.dataset.shareText,
        });
    });
    modal.addEventListener('click', function (e) {
        const opt = e.target.closest('[data-share-target]');
        if (!opt) return;
        const target = opt.dataset.shareTarget;
        const text = encodeURIComponent(currentText);
        const url = encodeURIComponent(currentUrl);
        const title = encodeURIComponent(currentTitle);
        const links = {
            whatsapp: `https://wa.me/?text=${text}%20${url}`,
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
            twitter: `https://twitter.com/intent/tweet?text=${text}&url=${url}`,
            telegram: `https://t.me/share/url?url=${url}&text=${text}`,
            email: `mailto:?subject=${title}&body=${text}%20${url}`,
        };
        if (target === 'copy') { copyToClipboard(currentUrl); return; }
        const win = window.open(links[target], '_blank', 'noopener,width=600,height=600');
        if (win) closeShare();
    });
    copyBtn.addEventListener('click', () => copyToClipboard(currentUrl));

    function copyToClipboard(value) {
        const fallback = () => {
            const ta = document.createElement('textarea');
            ta.value = value;
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); } catch (_) {}
            document.body.removeChild(ta);
        };
        const after = () => {
            copyBtn.classList.add('is-copied');
            copyBtn.textContent = 'Tersalin ✓';
            setTimeout(() => {
                copyBtn.classList.remove('is-copied');
                copyBtn.textContent = 'Salin';
            }, 1800);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(value).then(after).catch(() => { fallback(); after(); });
        } else {
            fallback(); after();
        }
    }
})();
</script>
@endpush
