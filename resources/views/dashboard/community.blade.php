@extends('layouts.dashboard')

@section('page-title', 'Komunitas')

@section('dashboard-content')
@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

<div id="communityContent" class="page-content active">
    <section class="community-hero">
        <div class="hero-left">
            <p class="hero-kicker">Komunitas</p>
            <h2>Berbagi pengalaman sesama pengusaha</h2>
        </div>
        <a href="{{ route('dashboard.community.create') }}" class="hero-write-btn">
            <span class="plus">+</span> Tulis
        </a>
    </section>

    <div class="community-search-wrap">
        <input type="text" id="communitySearch" placeholder="Cari cerita atau tips..." class="community-search" autocomplete="off">
    </div>

    <div class="community-chips" data-chips>
        <button class="chip active" type="button" data-filter="all">Semua</button>
        <button class="chip" type="button" data-filter="Kisah Sukses">Kisah Sukses</button>
        <button class="chip" type="button" data-filter="Tips & Trik">Tips & Trik</button>
        <button class="chip" type="button" data-filter="Tantangan">Tantangan</button>
        <button class="chip" type="button" data-filter="Lainnya">Lainnya</button>
    </div>

    <div class="posts-list" id="postsList">
        @forelse($posts as $post)
        <article class="post-card"
                 data-title="{{ strtolower($post['title']) }}"
                 data-category="{{ $post['category'] }}"
                 data-author="{{ strtolower($post['author']) }}"
                 data-excerpt="{{ strtolower($post['excerpt']) }}"
                 data-liked="{{ $post['liked'] ? '1' : '0' }}">
            <div class="post-top">
                <span class="post-badge" data-category="{{ $post['category'] }}">{{ $post['category'] }}</span>
                @if ($post['is_owner'])
                    <div class="post-owner-actions">
                        <a href="{{ route('dashboard.community.edit', $post['id']) }}" class="owner-btn" title="Edit">
                            <i data-lucide="pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('dashboard.community.destroy', $post['id']) }}" data-confirm="Hapus artikel ini?">@csrf @method('DELETE')
                            <button class="owner-btn owner-btn-danger" type="submit" title="Hapus">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <a href="{{ route('dashboard.community.show', $post['id']) }}" class="post-link">
                <h3 class="post-title">{{ $post['title'] }}</h3>
                <p class="post-excerpt">{{ $post['excerpt'] }}</p>
            </a>

            <div class="post-author">
                <img src="{{ $post['avatar'] }}" alt="{{ $post['author'] }}" class="author-avatar"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post['author']) }}&background=0f5a34&color=fff'">
                <div class="author-info">
                    <strong>{{ $post['author'] }}</strong>
                    <span class="author-role">{{ $post['author_role'] }}</span>
                </div>
            </div>

            <div class="post-stats">
                <button type="button" class="stat-item stat-like" data-like-btn aria-label="Suka" aria-pressed="false">
                    <i data-lucide="heart" class="like-icon"></i>
                    <span data-like-count>{{ $post['likes'] }}</span>
                </button>
                <span class="stat-item"><i data-lucide="message-circle"></i> {{ $post['comments'] }}</span>
                <span class="stat-item"><i data-lucide="clock"></i> {{ $post['date'] }}</span>
                <button type="button" class="stat-item stat-share" data-share-button
                        data-share-title="{{ $post['title'] }}"
                        data-share-url="{{ route('dashboard.community.show', $post['id']) }}"
                        aria-label="Bagikan">
                    <i data-lucide="share-2"></i>
                    <span>Bagikan</span>
                </button>
            </div>
        </article>
        @empty
        <div class="empty-state">
            <h3>Belum ada artikel</h3>
            <p>Jadilah yang pertama membagikan cerita untuk UMKM lainnya.</p>
            <a href="{{ route('dashboard.community.create') }}" class="hero-write-btn"><span class="plus">+</span> Tulis</a>
        </div>
        @endforelse

        <div class="empty-state" id="noResults" hidden>
            <h3>Tidak ada artikel yang cocok</h3>
            <p>Coba kata kunci lain atau ubah filter kategori.</p>
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
                <span class="share-icon" style="background:#25D366;">W</span>
                <span class="share-label">WhatsApp</span>
            </button>
            <button type="button" class="share-option" data-share-target="facebook">
                <span class="share-icon" style="background:#1877F2;">f</span>
                <span class="share-label">Facebook</span>
            </button>
            <button type="button" class="share-option" data-share-target="twitter">
                <span class="share-icon" style="background:#000000;">X</span>
                <span class="share-label">Twitter / X</span>
            </button>
            <button type="button" class="share-option" data-share-target="telegram">
                <span class="share-icon" style="background:#229ED9;">T</span>
                <span class="share-label">Telegram</span>
            </button>
            <button type="button" class="share-option" data-share-target="email">
                <span class="share-icon" style="background:#EA4335;">@</span>
                <span class="share-label">Email</span>
            </button>
            <button type="button" class="share-option" data-share-target="copy">
                <span class="share-icon" style="background:#0f5a34;">L</span>
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

/* Hero */
.community-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 24px 28px;
    background: #005A2B;
    color: #fff;
    border-bottom-left-radius: 28px;
    border-bottom-right-radius: 28px;
    margin: -24px -24px 22px;
    box-shadow: 0 6px 18px rgba(0, 90, 43, .18);
}
@media (min-width: 1024px) { .community-hero { margin: 0 0 22px; } }
.hero-left { flex: 1; }
.hero-kicker {
    margin: 0 0 6px;
    font-size: 12px;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .78;
    font-weight: 700;
}
.community-hero h2 { margin: 0; font-size: 22px; line-height: 1.25; font-weight: 700; }
.hero-write-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: #005A2B;
    font-weight: 700;
    border-radius: 999px;
    padding: 0 18px;
    height: 40px;
    min-width: 90px;
    text-decoration: none;
    font-size: 14px;
    transition: transform .15s ease;
}
.hero-write-btn:hover { transform: scale(1.04); }
.hero-write-btn .plus { font-size: 18px; line-height: 1; font-weight: 700; }

/* Search */
.community-search-wrap { margin-bottom: 14px; }
.community-search {
    width: 100%;
    border: 1px solid #e2e8f0;
    background: #fff;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    color: #0f172a;
}
.community-search::placeholder { color: #94a3b8; }
.community-search:focus { outline: none; border-color: #005A2B; box-shadow: 0 0 0 3px rgba(0, 90, 43, .12); }

/* Chips */
.community-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; padding-bottom: 4px; }
.chip {
    border: none;
    background: #F3F3F3;
    color: #333;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s ease, color .15s ease;
}
.chip:hover { background: #e6e6e6; }
.chip.active { background: #005A2B; color: #fff; }

/* Card */
.posts-list { display: flex; flex-direction: column; gap: 14px; }
.post-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 4px 14px rgba(15, 23, 42, .06);
    border: none;
}
.post-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.post-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    color: #0a3d1a;
}
.post-badge[data-category="Kisah Sukses"] { background: #7EE5A0; }
.post-badge[data-category="Tips & Trik"] { background: #16a34a; color: #fff; }
.post-badge[data-category="Tantangan"] { background: #f59e0b; color: #fff; }
.post-badge[data-category="Lainnya"] { background: #e2e8f0; color: #334155; }
.post-owner-actions { display: flex; gap: 4px; }
.owner-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #475569;
    border: none;
    cursor: pointer;
    text-decoration: none;
}
.owner-btn i { width: 14px; height: 14px; }
.owner-btn:hover { background: #e2e8f0; }
.owner-btn-danger { color: #dc2626; }
.owner-btn-danger:hover { background: #fee2e2; }
.post-link { text-decoration: none; color: inherit; display: block; margin-bottom: 14px; }
.post-title {
    margin: 0 0 8px;
    font-size: 17px;
    font-weight: 700;
    line-height: 1.4;
    color: #0f172a;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.post-excerpt {
    margin: 0;
    font-size: 14px;
    line-height: 1.55;
    color: #54685f;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Author */
.post-author { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.author-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.author-info { display: flex; flex-direction: column; line-height: 1.2; }
.author-info strong { font-size: 13px; color: #0f172a; font-weight: 600; }
.author-role { font-size: 11px; color: #94a3b8; }

/* Stats */
.post-stats { display: flex; align-items: center; gap: 14px; padding-top: 12px; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
.stat-item { display: inline-flex; align-items: center; gap: 5px; color: #94a3b8; font-size: 12px; font-weight: 600; background: none; border: none; padding: 4px 6px; border-radius: 8px; cursor: pointer; font-family: inherit; transition: background .15s ease, transform .12s ease; }
.stat-item i { width: 14px; height: 14px; transition: transform .2s cubic-bezier(.34,1.56,.64,1), fill .2s ease; }
button.stat-item:hover { background: #f1f5f9; }
button.stat-item:active { transform: scale(.92); }
button.stat-like.is-liked { color: #dc2626; background: #fee2e2; }
button.stat-like.is-liked i { fill: #dc2626; stroke: #dc2626; }
button.stat-like.is-liked i { animation: heartPop .45s cubic-bezier(.34,1.56,.64,1); }
button.stat-share { color: #005A2B; }
button.stat-share:hover { background: #e6f6ec; }
@keyframes heartPop {
    0%   { transform: scale(1); }
    30%  { transform: scale(1.45); }
    60%  { transform: scale(.9); }
    100% { transform: scale(1); }
}

.empty-state { background: #fff; border-radius: 16px; padding: 40px 24px; text-align: center; box-shadow: 0 4px 14px rgba(15, 23, 42, .05); }
.empty-state h3 { margin: 0 0 6px; color: #0f172a; }
.empty-state p { margin: 0 0 14px; color: #94a3b8; }

/* Like & Share button (post-detail action bar uses similar style) */
.like-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F5F5F5;
    border: none;
    border-radius: 14px;
    padding: 10px 16px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: background .15s ease;
}
.like-btn:hover { background: #ebebeb; }
.like-btn.is-liked { background: #fee2e2; color: #dc2626; }
.like-btn.is-liked .like-icon { fill: #dc2626; }
.like-icon { width: 16px; height: 16px; }

/* Share Modal */
.share-modal { position: fixed; inset: 0; z-index: 90; display: flex; align-items: center; justify-content: center; padding: 16px; }
.share-modal[hidden] { display: none !important; }
.share-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); backdrop-filter: blur(2px); cursor: pointer; }
.share-card { position: relative; z-index: 1; background: #fff; border-radius: 20px; padding: 26px 24px; max-width: 380px; width: 100%; box-shadow: 0 18px 40px rgba(15, 23, 42, .25); pointer-events: auto; }
.share-close { position: absolute; top: 12px; right: 12px; background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 20px; color: #475569; cursor: pointer; z-index: 2; display: inline-flex; align-items: center; justify-content: center; line-height: 1; transition: background .15s ease, transform .12s ease; }
.share-close:hover { background: #e2e8f0; transform: scale(1.05); }
.share-close:active { transform: scale(.95); }
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

    const search = document.getElementById('communitySearch');
    const cards = Array.from(document.querySelectorAll('.post-card'));
    const noResults = document.getElementById('noResults');
    const chips = document.querySelectorAll('[data-chips] .chip');
    let activeFilter = 'all';

    function applyFilter() {
        const q = (search && search.value || '').toLowerCase().trim();
        let visible = 0;
        cards.forEach(card => {
            const matchFilter = activeFilter === 'all' || card.dataset.category === activeFilter;
            const matchSearch = !q || (card.dataset.title + ' ' + card.dataset.excerpt + ' ' + card.dataset.author).includes(q);
            const show = matchFilter && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noResults) noResults.hidden = visible > 0;
    }

    if (search) search.addEventListener('input', applyFilter);
    chips.forEach(chip => chip.addEventListener('click', function () {
        chips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        activeFilter = chip.dataset.filter;
        applyFilter();
    }));

    document.querySelectorAll('[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!confirm(f.dataset.confirm)) e.preventDefault(); });
    });

    // Like button (toggle & animate)
    document.querySelectorAll('[data-like-btn]').forEach(function (btn) {
        const card = btn.closest('.post-card');
        const initiallyLiked = card && card.dataset.liked === '1';
        if (initiallyLiked) {
            btn.classList.add('is-liked');
            btn.setAttribute('aria-pressed', 'true');
        }
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const countEl = btn.querySelector('[data-like-count]');
            const wasLiked = btn.classList.contains('is-liked');
            btn.classList.toggle('is-liked');
            const isLiked = !wasLiked;
            btn.setAttribute('aria-pressed', isLiked ? 'true' : 'false');
            if (countEl) {
                const n = parseInt(countEl.textContent, 10) || 0;
                countEl.textContent = isLiked ? n + 1 : Math.max(0, n - 1);
            }
            // restart animation
            const ic = btn.querySelector('i');
            if (ic) {
                ic.style.animation = 'none';
                ic.offsetHeight; // reflow
                ic.style.animation = '';
            }
        });
    });

    // Share modal controller
    const modal = document.getElementById('shareModal');
    if (modal) {
        const titleEl = document.getElementById('shareModalTitle');
        const linkInput = document.getElementById('shareLinkInput');
        function closeShare() {
            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        function openShare(title, url) {
            if (titleEl) titleEl.textContent = title || '—';
            if (linkInput) linkInput.value = url || window.location.href;
            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
        // Close handlers — use mousedown to avoid backdrop swallowing close on the card area
        function handleClose(e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            closeShare();
        }
        document.querySelectorAll('[data-share-close]').forEach(function (el) {
            el.addEventListener('click', handleClose);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeShare();
        });
        document.querySelectorAll('[data-share-button]').forEach(function (b) {
            b.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openShare(b.dataset.shareTitle, b.dataset.shareUrl);
            });
        });
        document.querySelectorAll('[data-share-target]').forEach(function (opt) {
            opt.addEventListener('click', function () {
                const t = opt.dataset.shareTarget;
                const u = linkInput ? linkInput.value : window.location.href;
                const title = titleEl ? titleEl.textContent : '';
                const enc = encodeURIComponent;
                let target = '';
                if (t === 'whatsapp') target = 'https://wa.me/?text=' + enc(title + ' ' + u);
                else if (t === 'facebook') target = 'https://www.facebook.com/sharer/sharer.php?u=' + enc(u);
                else if (t === 'twitter') target = 'https://twitter.com/intent/tweet?text=' + enc(title) + '&url=' + enc(u);
                else if (t === 'telegram') target = 'https://t.me/share/url?url=' + enc(u) + '&text=' + enc(title);
                else if (t === 'email') target = 'mailto:?subject=' + enc(title) + '&body=' + enc(title + ' ' + u);
                else if (t === 'copy') {
                    if (navigator.clipboard) navigator.clipboard.writeText(u);
                    return;
                }
                if (target) window.open(target, '_blank', 'noopener');
            });
        });
        const copyBtn = document.getElementById('shareCopyBtn');
        if (copyBtn) copyBtn.addEventListener('click', function () {
            if (navigator.clipboard && linkInput) navigator.clipboard.writeText(linkInput.value);
            const orig = copyBtn.textContent;
            copyBtn.textContent = 'Tersalin!';
            setTimeout(function () { copyBtn.textContent = orig; }, 1400);
        });
    }
});
</script>
@endpush
