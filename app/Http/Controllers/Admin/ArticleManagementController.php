<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleManagementController extends Controller
{
    private const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const CATEGORIES = ['Kisah Sukses', 'Tips & Trik', 'Tantangan', 'Lainnya'];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $query = CommunityPost::query()->with('user')->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $articles = $query->paginate(12)->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $image = $this->extractBase64Image($request->input('thumbnail'));
        $user = Auth::user();
        $authorName = $user?->name ?: 'Admin Bookify';
        $avatar = 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&background=0f5a34&color=fff';
        $publishing = $request->boolean('is_published');

        CommunityPost::create([
            'user_id' => $user?->id,
            'author_name' => $authorName,
            'author_role' => 'Admin',
            'avatar_url' => $avatar,
            'cover_url' => $avatar,
            'category' => $data['category'],
            'title' => $data['title'],
            'excerpt' => $this->normalizeExcerpt($data['content'], $data['excerpt'] ?? null),
            'content' => $data['content'],
            'image_data' => $image['data'],
            'image_mime' => $image['mime'],
            'is_published' => $publishing,
            'published_at' => $publishing ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(CommunityPost $article): View
    {
        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function show(CommunityPost $article): View
    {
        $article->load(['user', 'comments.user']);

        return view('admin.articles.show', [
            'article' => $article,
            'comments' => $article->comments()->latest('id')->take(20)->get(),
        ]);
    }

    public function update(Request $request, CommunityPost $article): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $image = $this->extractBase64Image($request->input('thumbnail'));
        $publishing = $request->boolean('is_published');

        $article->category = $data['category'];
        $article->title = $data['title'];
        $article->excerpt = $this->normalizeExcerpt($data['content'], $data['excerpt'] ?? null);
        $article->content = $data['content'];

        if ($image['data']) {
            $article->image_data = $image['data'];
            $article->image_mime = $image['mime'];
        }

        if ($publishing && ! $article->is_published) {
            $article->published_at = now();
        }
        if (! $publishing) {
            $article->published_at = null;
        }
        $article->is_published = $publishing;
        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function togglePublish(CommunityPost $article): RedirectResponse
    {
        $article->is_published = ! $article->is_published;
        $article->published_at = $article->is_published ? ($article->published_at ?: now()) : null;
        $article->save();

        return back()->with('success', $article->is_published ? 'Artikel dipublikasikan.' : 'Artikel dikembalikan ke draft.');
    }

    public function destroy(CommunityPost $article): RedirectResponse
    {
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'in:'.implode(',', self::CATEGORIES)],
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:25'],
            'is_published' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'string'],
        ], [
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'title.required' => 'Judul wajib diisi.',
            'content.required' => 'Isi artikel wajib diisi.',
            'content.min' => 'Isi artikel minimal 25 karakter.',
        ]);
    }

    private function normalizeExcerpt(string $content, ?string $excerpt): string
    {
        $explicit = trim((string) $excerpt);
        if ($explicit !== '') {
            return $explicit;
        }

        $flat = preg_replace('/\s+/', ' ', strip_tags($content));

        return mb_strimwidth($flat, 0, 180, '…');
    }

    private function extractBase64Image(?string $value): array
    {
        if (! $value) {
            return ['data' => null, 'mime' => null];
        }
        if (! preg_match('#^data:(image/[a-z0-9.+-]+);base64,(.+)$#i', $value, $m)) {
            return ['data' => null, 'mime' => null];
        }
        $mime = strtolower($m[1]);
        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            return ['data' => null, 'mime' => null];
        }
        $bin = base64_decode($m[2], true);
        if ($bin === false || strlen($bin) > self::MAX_IMAGE_BYTES) {
            return ['data' => null, 'mime' => null];
        }
        return ['data' => base64_encode($bin), 'mime' => $mime];
    }
}
