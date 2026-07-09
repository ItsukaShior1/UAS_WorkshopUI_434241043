@extends('admin.layout')

@section('page-title', 'Detail Artikel Komunitas')

@section('admin-content')
<div class="admin-show-page">
    <div class="admin-show-header">
        <div>
            <a href="{{ route('admin.articles.index') }}" class="btn-admin-text">Kembali</a>
            <h2>{{ $article->title }}</h2>
            <p>Detail artikel komunitas yang dikelola admin.</p>
        </div>
        <div class="admin-show-header-actions">
            <form method="POST" action="{{ route('admin.articles.toggle', $article) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn-admin-secondary">
                    {{ $article->is_published ? 'Ubah ke Draft' : 'Publikasikan' }}
                </button>
            </form>
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn-admin-primary">Edit Artikel</a>
        </div>
    </div>

    <article class="admin-article-show-card">
        @if ($article->image_data)
            <div class="admin-cover-wrap">
                <img src="{{ $article->image_data_uri }}" alt="{{ $article->title }}" class="admin-cover-image">
            </div>
        @endif

        <div class="admin-article-meta-grid">
            <span class="meta-pill">{{ $article->category }}</span>
            <span class="meta-pill status-{{ $article->is_published ? 'published' : 'draft' }}">
                {{ $article->is_published ? 'Published' : 'Draft' }}
            </span>
            <span><i data-lucide="user"></i> {{ $article->user->name ?? $article->author_name ?? 'Anonim' }}</span>
            <span><i data-lucide="clock"></i> {{ optional($article->published_at ?? $article->updated_at)->format('d M Y H:i') }}</span>
            <span><i data-lucide="heart"></i> {{ (int) $article->likes_count }} suka</span>
            <span><i data-lucide="message-circle"></i> {{ (int) $article->comments_count }} komentar</span>
        </div>

        @if (!empty($article->excerpt))
            <div class="admin-excerpt">
                {{ $article->excerpt }}
            </div>
        @endif

        <div class="admin-article-content">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="admin-inline-actions">
            <a href="{{ route('dashboard.community.show', $article->id) }}" class="btn-admin-text">Lihat di Halaman User</a>
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline-form" onsubmit="return confirm('Hapus artikel {{ $article->title }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-admin-danger">Hapus Artikel</button>
            </form>
        </div>
    </article>

    <section class="admin-comments-card">
        <h3>Komentar Terbaru</h3>
        @if ($comments->isEmpty())
            <div class="empty-note">Belum ada komentar pada artikel ini.</div>
        @else
            <div class="comment-list">
                @foreach ($comments as $comment)
                    <article class="comment-item">
                        <div class="comment-head">
                            <strong>{{ $comment->user->name ?? $comment->author_name }}</strong>
                            <span>{{ optional($comment->created_at)->diffForHumans() }}</span>
                        </div>
                        <p>{{ $comment->body }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
.admin-show-page { display: flex; flex-direction: column; gap: 20px; max-width: 960px; }
.admin-show-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; }
.admin-show-header h2 { margin: 4px 0; color: #0f172a; }
.admin-show-header p { margin: 0; color: #64748b; font-size: 14px; }
.admin-show-header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

.admin-article-show-card,
.admin-comments-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(15,23,42,.04);
}

.admin-cover-wrap { border-radius: 14px; overflow: hidden; margin-bottom: 16px; }
.admin-cover-image { display: block; width: 100%; max-height: 360px; object-fit: cover; }

.admin-article-meta-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 14px;
    color: #64748b;
    font-size: 13px;
    margin-bottom: 14px;
}
.admin-article-meta-grid span { display: inline-flex; align-items: center; gap: 6px; }
.admin-article-meta-grid i { width: 14px; height: 14px; }

.meta-pill {
    padding: 4px 10px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #0f172a;
    font-weight: 700;
    font-size: 11px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.status-published { background: #dcfce7; color: #166534; }
.status-draft { background: #fef3c7; color: #92400e; }

.admin-excerpt {
    border-left: 4px solid #cbd5e1;
    padding: 10px 12px;
    background: #f8fafc;
    color: #475569;
    margin-bottom: 16px;
    border-radius: 10px;
    line-height: 1.6;
}

.admin-article-content {
    color: #1e293b;
    font-size: 15px;
    line-height: 1.8;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.admin-inline-actions {
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.btn-admin-primary,
.btn-admin-secondary,
.btn-admin-danger {
    border: none;
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.btn-admin-primary { background: #0f172a; color: #fff; }
.btn-admin-secondary { background: #334155; color: #fff; }
.btn-admin-danger { background: #b91c1c; color: #fff; }
.btn-admin-text { color: #64748b; text-decoration: none; font-weight: 600; }

.admin-comments-card h3 { margin: 0 0 14px; color: #0f172a; }
.empty-note { color: #94a3b8; }
.comment-list { display: flex; flex-direction: column; gap: 12px; }
.comment-item { border-top: 1px solid #f1f5f9; padding-top: 12px; }
.comment-item:first-child { border-top: none; padding-top: 0; }
.comment-head { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 6px; }
.comment-head strong { color: #0f172a; font-size: 14px; }
.comment-head span { color: #94a3b8; font-size: 12px; }
.comment-item p { margin: 0; color: #334155; line-height: 1.6; }
</style>
@endpush
