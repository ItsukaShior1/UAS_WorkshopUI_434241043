@extends('layouts.dashboard')

@section('page-title', $post['title'])

@section('dashboard-content')
@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

<div class="detail-shell">
    <header class="detail-header">
        <a href="{{ route('dashboard.community') }}" class="detail-back" aria-label="Kembali">
            <i data-lucide="arrow-left"></i>
        </a>
        <div class="detail-header-body">
            <span class="detail-category" data-category="{{ $post['category'] }}">{{ $post['category'] }}</span>
            <h1>{{ $post['title'] }}</h1>
            <div class="detail-meta">
                <strong>{{ $post['author'] }}</strong>
                <span class="detail-role">{{ $post['author_role'] }}</span>
                <span class="detail-date">{{ $post['date'] }}</span>
            </div>
        </div>
        @if ($post['is_owner'])
            <a href="{{ route('dashboard.community.edit', $post['id']) }}" class="detail-edit-btn" title="Edit">
                <i data-lucide="pencil"></i>
            </a>
        @endif
    </header>

    <article class="detail-article">
        @if (!empty($post['cover']) && str_starts_with($post['cover'], 'data:image'))
            <div class="detail-cover">
                <img src="{{ $post['cover'] }}" alt="{{ $post['title'] }}">
            </div>
        @endif

        <div class="detail-body">
            @foreach(preg_split("/\n\s*\n/", $post['content']) as $paragraph)
                @if(trim($paragraph) !== '')
                    <p>{{ $paragraph }}</p>
                @endif
            @endforeach
        </div>

        <div class="action-bar">
            <button class="like-btn {{ $post['liked'] ? 'is-liked' : '' }}" type="button"
                    data-like-button
                    data-like-url="{{ route('dashboard.community.like', $post['id']) }}">
                <svg class="like-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                </svg>
                <span data-like-count>{{ $post['likes'] }}</span>
            </button>
            <a href="#comments" class="like-btn">
                <i data-lucide="message-circle"></i>
                <span>{{ $post['comments'] }}</span>
            </a>
            <button class="like-btn" type="button"
                    data-share-button
                    data-share-url="{{ route('dashboard.community.show', $post['id'], false) }}"
                    data-share-title="{{ e($post['title']) }}"
                    data-share-text="{{ e($post['excerpt']) }}">
                <i data-lucide="share-2"></i>
                <span>Bagikan</span>
            </button>
        </div>
    </article>

    <section class="comments-section" id="comments">
        <h3>Komentar ({{ $post['comments'] }})</h3>

        <div class="comments-list">
            @forelse($comments as $comment)
            <div class="comment-item">
                <div class="comment-head">
                    <strong>{{ $comment['author'] }}</strong>
                    <span>{{ $comment['time'] }}</span>
                </div>
                <p>{{ $comment['text'] }}</p>
            </div>
            @empty
            <div class="comment-empty">Belum ada komentar. Jadi yang pertama berkomentar!</div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('dashboard.community.comment.store', $post['id']) }}" class="comment-form" novalidate>
            @csrf
            <textarea name="body" rows="4" maxlength="500" required minlength="2"
                      class="comment-input @error('body') is-invalid @enderror"
                      placeholder="Tulis komentar...">{{ old('body') }}</textarea>
            @error('body')<small class="form-error">{{ $message }}</small>@enderror
            <div class="comment-actions">
                <a href="{{ route('dashboard.community') }}" class="btn-cancel-comment">Batal</a>
                <button type="submit" class="btn-submit-comment">Kirim Komentar</button>
            </div>
        </form>
    </section>
</div>

{{-- Share Modal --}}
<div class="share-modal" id="shareModal" hidden aria-hidden="true" role="dialog" aria-labelledby="shareModalTitle">
    <div class="share-backdrop" data-share-close></div>
    <div class="share-card" role="document">
        <button class="share-close" type="button" data-share-close aria-label="Tutup">×</button>
        <p class="share-kicker">Bagikan Artikel</p>
        <h3 id="shareModalTitle">—</h3>
        <div class="share-grid">
            <button type="button" class="share-option" data-share-target="whatsapp"><span class="share-icon" style="background:#25D366;">W</span><span class="share-label">WhatsApp</span></button>
            <button type="button" class="share-option" data-share-target="facebook"><span class="share-icon" style="background:#1877F2;">f</span><span class="share-label">Facebook</span></button>
            <button type="button" class="share-option" data-share-target="twitter"><span class="share-icon" style="background:#000000;">X</span><span class="share-label">Twitter / X</span></button>
            <button type="button" class="share-option" data-share-target="telegram"><span class="share-icon" style="background:#229ED9;">T</span><span class="share-label">Telegram</span></button>
            <button type="button" class="share-option" data-share-target="email"><span class="share-icon" style="background:#EA4335;">@</span><span class="share-label">Email</span></button>
            <button type="button" class="share-option" data-share-target="copy"><span class="share-icon" style="background:#0f5a34;">L</span><span class="share-label">Salin Tautan</span></button>
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
.flash-success { margin-bottom: 14px; padding: 12px 16px; border-radius: 14px; background: #e6f6ec; color: #0f5a34; border: 1px solid #b9e3c8; font-weight: 600; }

/* Header */
.detail-shell { max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 18px; }
.detail-header {
    position: relative;
    background: #005A2B;
    color: #fff;
    padding: 22px 24px;
    border-bottom-left-radius: 28px;
    border-bottom-right-radius: 28px;
    margin: -24px -24px 0;
    min-height: 200px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
@media (min-width: 1024px) { .detail-header { margin: 0; } }
.detail-back { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,.15); color: #fff; text-decoration: none; flex-shrink: 0; }
.detail-back i { width: 18px; height: 18px; }
.detail-back:hover { background: rgba(255,255,255,.25); }
.detail-header-body { flex: 1; padding-top: 4px; }
.detail-category {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    background: rgba(255,255,255,.2);
    color: #fff;
    margin-bottom: 10px;
}
.detail-header h1 { margin: 0 0 12px; font-size: 22px; line-height: 1.3; color: #fff; }
.detail-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; font-size: 13px; color: rgba(255,255,255,.92); }
.detail-meta strong { font-weight: 600; }
.detail-role { padding: 2px 8px; background: rgba(255,255,255,.18); border-radius: 999px; font-size: 11px; }
.detail-date { color: rgba(255,255,255,.7); }
.detail-edit-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,.15); color: #fff; text-decoration: none; flex-shrink: 0; }
.detail-edit-btn i { width: 16px; height: 16px; }
.detail-edit-btn:hover { background: rgba(255,255,255,.25); }

/* Article */
.detail-article { background: #fff; border-radius: 18px; padding: 24px; box-shadow: 0 4px 14px rgba(15, 23, 42, .05); }
.detail-cover { margin-bottom: 18px; }
.detail-cover img { display: block; width: 100%; max-height: 340px; object-fit: cover; border-radius: 16px; }
.detail-body { color: #334155; line-height: 1.85; font-size: 16px; overflow-wrap: anywhere; word-break: break-word; }
.detail-body p { margin: 0 0 16px; }
.detail-body p:last-child { margin-bottom: 0; }

/* Action Bar */
.action-bar { display: flex; gap: 10px; padding: 18px 0 4px; margin-top: 18px; border-top: 1px solid #f1f5f9; }
.like-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #F5F5F5;
    border: none;
    border-radius: 14px;
    padding: 10px 18px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: background .15s ease, color .15s ease;
}
.like-btn:hover { background: #ebebeb; }
.like-btn i, .like-btn .like-icon { width: 16px; height: 16px; }
.like-btn.is-liked { background: #fee2e2; color: #dc2626; }
.like-btn.is-liked .like-icon { fill: #dc2626; }

/* Comments */
.comments-section { background: #fff; border-radius: 18px; padding: 24px; box-shadow: 0 4px 14px rgba(15, 23, 42, .05); display: flex; flex-direction: column; gap: 18px; }
.comments-section h3 { margin: 0; color: #0f172a; font-size: 17px; }
.comments-list { display: flex; flex-direction: column; gap: 14px; }
.comment-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.comment-item:last-child { border-bottom: none; }
.comment-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.comment-head strong { color: #0f172a; font-size: 14px; }
.comment-head span { color: #94a3b8; font-size: 12px; }
.comment-item p { margin: 0; color: #334155; font-size: 14px; line-height: 1.6; }
.comment-empty { text-align: center; padding: 30px 20px; color: #94a3b8; background: #f8fafc; border-radius: 12px; }

/* Comment Form */
.comment-form { display: flex; flex-direction: column; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 18px; }
.comment-input {
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 14px;
    font-family: inherit;
    color: #0f172a;
    background: #fff;
    min-height: 80px;
    resize: vertical;
}
.comment-input:focus { outline: none; border-color: #005A2B; box-shadow: 0 0 0 3px rgba(0, 90, 43, .12); }
.comment-input.is-invalid { border-color: #dc2626; }
.form-error { color: #dc2626; font-size: 12px; font-weight: 600; }
.comment-actions { display: flex; gap: 10px; }
.btn-cancel-comment { flex: 1; padding: 12px; border-radius: 12px; background: #f1f5f9; color: #475569; text-decoration: none; text-align: center; font-weight: 600; font-size: 14px; border: 1px solid #cbd5e1; }
.btn-cancel-comment:hover { background: #e2e8f0; }
.btn-submit-comment { flex: 2; padding: 12px; border-radius: 12px; background: #005A2B; color: #fff; border: none; font-weight: 700; font-size: 14px; cursor: pointer; }
.btn-submit-comment:hover { background: #004521; }

/* Share Modal */
.share-modal { position: fixed; inset: 0; z-index: 90; display: flex; align-items: center; justify-content: center; }
.share-modal[hidden] { display: none !important; }
.share-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); backdrop-filter: blur(2px); }
.share-card { position: relative; background: #fff; border-radius: 20px; padding: 26px 24px; max-width: 380px; width: calc(100% - 32px); box-shadow: 0 18px 40px rgba(15, 23, 42, .25); }
.share-close { position: absolute; top: 12px; right: 12px; background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 20px; color: #475569; cursor: pointer; }
.share-kicker { margin: 0 0 4px; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }
.share-card h3 { margin: 0 0 18px; color: #0f172a; font-size: 18px; line-height: 1.35; }
.share-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 18px; }
.share-option { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 10px 6px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: transform .12s ease, border-color .12s ease; }
.share-option:hover { transform: translateY(-2px); border-color: #cbd5e1; }
.share-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
.share-label { font-size: 11px; color: #475569; font-weight: 600; }
.share-link-row { display: flex; gap: 8px; padding-top: 14px; border-top: 1px solid #f1f5f9; }
.share-link-row input { flex: 1; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 12px; background: #f8fafc; }
.share-link-row button { background: #005A2B; color: #fff; border: none; border-radius: 10px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();

    // Like toggle
    document.querySelectorAll('[data-like-button]').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const url = btn.dataset.likeUrl;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value;
            btn.disabled = true;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                const count = btn.querySelector('[data-like-count]');
                if (count) count.textContent = data.likes;
                btn.classList.toggle('is-liked', !!data.liked);
            } catch (err) {
                // Fallback: submit form
                const f = document.createElement('form');
                f.method = 'POST'; f.action = url;
                const t = document.createElement('input'); t.type = 'hidden'; t.name = '_token'; t.value = csrf; f.appendChild(t);
                document.body.appendChild(f); f.submit();
            } finally {
                btn.disabled = false;
            }
        });
    });

    // Share modal
    let currentTitle = '', currentUrl = '';
    const modal = document.getElementById('shareModal');
    const titleEl = document.getElementById('shareModalTitle');
    const linkInput = document.getElementById('shareLinkInput');
    function openShare(btn) {
        currentTitle = btn.dataset.shareTitle || '';
        const text = btn.dataset.shareText || '';
        currentUrl = btn.dataset.shareUrl || window.location.href;
        titleEl.textContent = currentTitle;
        linkInput.value = currentUrl;
        modal.hidden = false;
    }
    function closeShare() { modal.hidden = true; }
    document.querySelectorAll('[data-share-button]').forEach(b => b.addEventListener('click', () => openShare(b)));
    document.querySelectorAll('[data-share-close]').forEach(el => el.addEventListener('click', closeShare));
    document.querySelectorAll('[data-share-target]').forEach(function (opt) {
        opt.addEventListener('click', function () {
            const t = opt.dataset.shareTarget;
            const enc = encodeURIComponent;
            const u = currentUrl, title = currentTitle, txt = '';
            let target = '';
            if (t === 'whatsapp') target = 'https://wa.me/?text=' + enc(title + ' ' + u);
            else if (t === 'facebook') target = 'https://www.facebook.com/sharer/sharer.php?u=' + enc(u);
            else if (t === 'twitter') target = 'https://twitter.com/intent/tweet?text=' + enc(title) + '&url=' + enc(u);
            else if (t === 'telegram') target = 'https://t.me/share/url?url=' + enc(u) + '&text=' + enc(title);
            else if (t === 'email') target = 'mailto:?subject=' + enc(title) + '&body=' + enc(title + ' ' + u);
            else if (t === 'copy') {
                navigator.clipboard?.writeText(u).then(() => {
                    const btn = document.getElementById('shareCopyBtn');
                    if (btn) { const orig = btn.textContent; btn.textContent = 'Tersalin!'; setTimeout(() => btn.textContent = orig, 1400); }
                });
                return;
            }
            if (target) window.open(target, '_blank', 'noopener');
        });
    });
    const copyBtn = document.getElementById('shareCopyBtn');
    if (copyBtn) copyBtn.addEventListener('click', function () {
        navigator.clipboard?.writeText(linkInput.value);
        const orig = copyBtn.textContent; copyBtn.textContent = 'Tersalin!'; setTimeout(() => copyBtn.textContent = orig, 1400);
    });
});
</script>
@endpush
