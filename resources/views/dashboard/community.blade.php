@extends('layouts.dashboard')

@section('page-title', 'Komunitas')

@section('dashboard-content')
@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

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
            <input type="text" id="communitySearch" placeholder="Cari artikel atau topik..." class="search-input" autocomplete="off">
        </div>

        <div class="community-tabs" data-tabs>
            <button class="tab-btn active" type="button" data-tab="terbaru">Terbaru</button>
            <button class="tab-btn" type="button" data-tab="populer">Populer</button>
            <button class="tab-btn" type="button" data-tab="terdekat">Terdekat</button>
        </div>
    </div>

    <div class="posts-list" id="postsList">
        @forelse($posts as $post)
        <article class="post-card"
                 data-title="{{ strtolower($post['title']) }}"
                 data-category="{{ strtolower($post['category']) }}"
                 data-author="{{ strtolower($post['author']) }}"
                 data-date="{{ $post['date'] }}"
                 data-likes="{{ (int) $post['likes'] }}"
                 data-tab="terbaru">
            <a href="{{ route('dashboard.community.show', $post['id']) }}" class="post-link">
                <div class="post-cover" style="background-image: url('{{ $post['cover'] }}')">
                    <span class="post-badge">{{ $post['category'] }}</span>
                </div>

                <div class="post-body">
                    <div class="post-header">
                        <div class="post-author">
                            <img src="{{ $post['avatar'] }}" alt="{{ $post['author'] }}" class="author-avatar"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post['author']) }}&background=0f5a34&color=fff'">
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
                <button class="post-action like-toggle" type="button"
                        data-like-button
                        data-like-url="{{ route('dashboard.community.like', $post['id']) }}">
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/>
                    </svg>
                    <span data-like-count>{{ $post['likes'] }}</span>
                </button>
                <a class="post-action" href="{{ route('dashboard.community.show', $post['id']) }}#comments">
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M21 6h-18c-1.1 0-2 .9-2 2v12l4-4h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 7H7v-2h10v2zm3-4H7V7h13v2z" fill="currentColor"/>
                    </svg>
                    <span>{{ $post['comments'] }}</span>
                </a>
                <button class="post-action" type="button"
                        data-share-button
                        data-share-url="{{ route('dashboard.community.show', $post['id'], false) }}"
                        data-share-title="{{ e($post['title']) }}"
                        data-share-text="{{ e($post['excerpt']) }}">
                    <svg class="action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M18 16.08c-.76 0-1.44.3-1.96.78l-7.12-4.16c.05-.23.08-.46.08-.7s-.03-.47-.08-.7L16.04 7.2A2.99 2.99 0 0 0 21 5c0-1.66-1.34-3-3-3s-3 1.34-3 3c0 .24.03.47.08.7L8.96 9.86A2.99 2.99 0 0 0 4 12c0 1.66 1.34 3 3 3 .76 0 1.44-.3 1.96-.78l7.12 4.16c-.05.23-.08.46-.08.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                    </svg>
                    <span>Bagikan</span>
                </button>
            </div>
        </article>
        @empty
        <div class="empty-state">
            <h3>Belum ada artikel</h3>
            <p>Jadilah yang pertama membagikan cerita untuk UMKM lainnya.</p>
            <a href="{{ route('dashboard.community.create') }}" class="hero-action">+ Tulis Artikel</a>
        </div>
        @endforelse

        <div class="empty-state" id="noResults" hidden>
            <h3>Tidak ada artikel yang cocok</h3>
            <p>Coba kata kunci lain atau ubah filter tab aktif.</p>
        </div>
    </div>
</div>

{{-- Share Modal --}}
<div class="share-modal" id="shareModal" hidden aria-hidden="true" role="dialog" aria-labelledby="shareModalTitle">
    <div class="share-backdrop" data-share-close></div>
    <div class="share-card" role="document">
        <button class="share-close" type="button" data-share-close aria-label="Tutup">×</button>
        <p class="share-kicker">Bagikan Artikel</p>
        <h3 id="shareModalTitle">—</h3>

        <div class="share-grid">
            <button type="button" class="share-option" data-share-target="whatsapp">
                <span class="share-icon" style="background:#25D366;">💬</span>
                <span class="share-label">WhatsApp</span>
            </button>
            <button type="button" class="share-option" data-share-target="facebook">
                <span class="share-icon" style="background:#1877F2;">f</span>
                <span class="share-label">Facebook</span>
            </button>
            <button type="button" class="share-option" data-share-target="twitter">
                <span class="share-icon" style="background:#000000;">𝕏</span>
                <span class="share-label">Twitter / X</span>
            </button>
            <button type="button" class="share-option" data-share-target="telegram">
                <span class="share-icon" style="background:#229ED9;">✈</span>
                <span class="share-label">Telegram</span>
            </button>
            <button type="button" class="share-option" data-share-target="email">
                <span class="share-icon" style="background:#EA4335;">✉</span>
                <span class="share-label">Email</span>
            </button>
            <button type="button" class="share-option" data-share-target="copy">
                <span class="share-icon" style="background:#0f5a34;">🔗</span>
                <span class="share-label">Salin Tautan</span>
            </button>
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
    transition: all .2s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
}
.hero-action:hover {
    background: #fff;
    transform: translateY(-1px);
}
.community-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin: 18px 0 22px;
    flex-wrap: wrap;
}
.community-search {
    flex: 1;
    min-width: 220px;
}
.search-input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #d9e2dc;
    border-radius: 14px;
    font-size: 14px;
    background: #fff;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.search-input:focus {
    outline: none;
    border-color: #0f5a34;
    box-shadow: 0 0 0 3px rgba(15, 90, 52, .12);
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
.tab-btn:hover {
    background: #eaf4ee;
    color: #0f5a34;
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
    gap: 8px;
}
.post-author {
    display: flex;
    gap: 12px;
    align-items: center;
    min-width: 0;
}
.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 6px 16px rgba(0,0,0,.12);
    flex-shrink: 0;
}
.post-author h4 {
    margin: 0;
    font-size: 14px;
    color: #11261b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}
.post-category {
    margin: 2px 0 0 0;
    font-size: 12px;
    color: #628174;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}
.post-meta {
    font-size: 12px;
    font-weight: 600;
    color: #628174;
    white-space: nowrap;
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
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
.action-icon {
    width: 16px;
    height: 16px;
    flex: 0 0 16px;
}
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 48px 24px;
    background: #fff;
    border-radius: 20px;
    border: 1px dashed #cfe1d6;
}
.empty-state h3 {
    margin: 0 0 8px;
    color: #123122;
}
.empty-state p {
    margin: 0 0 18px;
    color: #54685f;
}

/* Share Modal */
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

@media (max-width: 900px) {
    .posts-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 768px) {
    .community-hero,
    .community-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .posts-list { grid-template-columns: 1fr; }
    .community-hero h2 { font-size: 22px; }
    .post-content h3 { font-size: 16px; }
    .post-footer { padding: 14px; }
    .share-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
    .share-card { padding: 22px 18px 18px; }
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

    // Tabs + Search filtering
    const tabButtons = document.querySelectorAll('[data-tabs] .tab-btn');
    const searchInput = document.getElementById('communitySearch');
    const cards = Array.from(document.querySelectorAll('.post-card'));
    const noResults = document.getElementById('noResults');
    let activeTab = 'terbaru';

    function applyFilter() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        let visible = 0;
        const sorted = cards.slice();
        if (activeTab === 'populer') {
            sorted.sort((a, b) => Number(b.dataset.likes || 0) - Number(a.dataset.likes || 0));
        }
        sorted.forEach(card => {
            const matchesTab = activeTab !== 'terdekat' || true;
            const matchesQuery = !q
                || (card.dataset.title || '').includes(q)
                || (card.dataset.category || '').includes(q)
                || (card.dataset.author || '').includes(q);
            const show = matchesTab && matchesQuery;
            card.hidden = !show;
            if (show) visible++;
        });
        if (noResults) noResults.hidden = visible > 0;
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeTab = btn.dataset.tab;
            applyFilter();
        });
    });
    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    // Share Modal
    const modal = document.getElementById('shareModal');
    if (modal) {
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
        console.log('[share] opened', data);
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
        e.stopPropagation();
        console.log('[share] button clicked', btn.dataset);
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
        if (target === 'copy') {
            copyToClipboard(currentUrl);
            return;
        }
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
    }
})();
</script>
@endpush
