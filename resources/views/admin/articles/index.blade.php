@extends('admin.layout')

@section('page-title', 'Manajemen Artikel Komunitas')

@section('admin-content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Manajemen Artikel Komunitas</h2>
            <p>Kelola artikel tulisan pengguna yang tampil di halaman Komunitas. Artikel yang dipublikasikan tampil beranda anggota.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn-admin-primary">
            <i data-lucide="plus"></i>
            <span>Artikel Baru</span>
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.articles.index') }}" class="admin-filter-bar">
        <div class="admin-filter-field">
            <i data-lucide="search"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul atau excerpt...">
        </div>
        <select name="status" class="admin-filter-select">
            <option value="">Semua Status</option>
            <option value="published" @selected($status === 'published')>Published</option>
            <option value="draft" @selected($status === 'draft')>Draft</option>
        </select>
        <button type="submit" class="btn-admin-secondary">Filter</button>
        @if ($search || $status)
            <a href="{{ route('admin.articles.index') }}" class="btn-admin-text">Reset</a>
        @endif
    </form>

    <div class="admin-article-grid">
        @forelse ($articles as $article)
            <article class="admin-article-card">
                <div class="admin-article-thumb">
                    @if ($article->image_data)
                        <img src="{{ $article->image_data_uri }}" alt="{{ $article->title }}">
                    @else
                        <div class="admin-article-thumb-fallback">
                            <i data-lucide="file-text"></i>
                        </div>
                    @endif
                    <span class="admin-article-status status-{{ $article->is_published ? 'published' : 'draft' }}">
                        {{ $article->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div class="admin-article-body">
                    <h3>{{ $article->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($article->excerpt ?: strip_tags($article->content), 110) }}</p>
                    <div class="admin-article-meta">
                        <span><i data-lucide="user"></i> {{ $article->user->name ?? $article->author_name ?? 'Anonim' }}</span>
                        <span><i data-lucide="tag"></i> {{ $article->category }}</span>
                        <span><i data-lucide="clock"></i> {{ optional($article->published_at ?? $article->updated_at)->format('d M Y') }}</span>
                    </div>
                    <div class="admin-article-actions">
                        <a href="{{ route('admin.articles.show', $article) }}" class="btn-icon" title="Lihat detail"><i data-lucide="eye"></i></a>
                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn-icon"><i data-lucide="pencil"></i></a>
                        <form method="POST" action="{{ route('admin.articles.toggle', $article) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn-icon" title="{{ $article->is_published ? 'Kembalikan ke draft' : 'Publikasikan' }}">
                                <i data-lucide="{{ $article->is_published ? 'eye-off' : 'send' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline-form" onsubmit="return confirm('Hapus artikel {{ $article->title }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-danger" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-empty-card">Belum ada artikel.</div>
        @endforelse
    </div>

    <div class="admin-pagination">{{ $articles->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.admin-page { display: flex; flex-direction: column; gap: 20px; }
.admin-page-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; }
.admin-page-header h2 { margin: 0 0 4px; color: #0f172a; }
.admin-page-header p { margin: 0; color: #64748b; font-size: 14px; }
.btn-admin-primary { display: inline-flex; align-items: center; gap: 8px; background: #0f172a; color: #fff; padding: 12px 18px; border-radius: 14px; text-decoration: none; font-weight: 600; }
.btn-admin-primary i { width: 18px; height: 18px; }
.btn-admin-secondary { background: #0f172a; color: #fff; padding: 12px 18px; border-radius: 14px; border: none; cursor: pointer; font-weight: 600; }
.btn-admin-text { color: #64748b; text-decoration: none; padding: 12px 8px; font-weight: 600; }
.admin-filter-bar { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; background: #fff; padding: 14px; border-radius: 16px; border: 1px solid #e2e8f0; }
.admin-filter-field { flex: 1; min-width: 220px; display: flex; align-items: center; gap: 8px; background: #f1f5f9; border-radius: 12px; padding: 10px 14px; }
.admin-filter-field i { width: 16px; height: 16px; color: #64748b; }
.admin-filter-field input { border: none; background: transparent; flex: 1; outline: none; font-size: 14px; }
.admin-filter-select { background: #f1f5f9; border: none; border-radius: 12px; padding: 12px 14px; font-size: 14px; min-width: 160px; }
.admin-alert { padding: 12px 16px; border-radius: 12px; font-weight: 600; }
.admin-alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.admin-article-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px; }
.admin-article-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 6px 18px rgba(15,23,42,.04); display: flex; flex-direction: column; }
.admin-article-thumb { position: relative; aspect-ratio: 16/9; background: #f1f5f9; overflow: hidden; }
.admin-article-thumb img { width: 100%; height: 100%; object-fit: cover; }
.admin-article-thumb-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; }
.admin-article-thumb-fallback i { width: 48px; height: 48px; }
.admin-article-status { position: absolute; top: 10px; left: 10px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
.status-published { background: #dcfce7; color: #166534; }
.status-draft { background: #fef3c7; color: #92400e; }
.admin-article-body { padding: 16px; display: flex; flex-direction: column; gap: 8px; }
.admin-article-body h3 { margin: 0; color: #0f172a; font-size: 16px; line-height: 1.35; }
.admin-article-body p { margin: 0; color: #64748b; font-size: 13px; line-height: 1.5; }
.admin-article-meta { display: flex; gap: 14px; color: #94a3b8; font-size: 12px; }
.admin-article-meta span { display: inline-flex; align-items: center; gap: 4px; }
.admin-article-meta i { width: 12px; height: 12px; }
.admin-article-actions { display: flex; gap: 8px; padding-top: 10px; border-top: 1px solid #f1f5f9; margin-top: 6px; }
.btn-icon { background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; text-decoration: none; }
.btn-icon:hover { background: #e2e8f0; }
.btn-icon i { width: 16px; height: 16px; }
.btn-icon-danger { color: #b91c1c; }
.btn-icon-danger:hover { background: #fee2e2; }
.inline-form { display: inline; margin: 0; }
.admin-empty-card { grid-column: 1 / -1; text-align: center; color: #94a3b8; padding: 60px 0; background: #fff; border-radius: 18px; border: 1px dashed #cbd5e1; }
.admin-pagination { margin-top: 8px; }
</style>
@endpush
