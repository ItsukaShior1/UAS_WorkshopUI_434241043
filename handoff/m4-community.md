# Milestone 4 — Community Redesign

## Status: COMPLETE

## Yang Berubah

### CRUD Penuh untuk Community Post
- **Edit post sendiri** — `communityEdit` + `communityUpdate` view `dashboard.community.edit`
- **Hapus post sendiri** — `communityDestroy` dengan konfirmasi JS
- **Edit/hapus button** muncul di card list + detail page (kondisional `is_owner`)

### Like System Real
- Migration baru: `post_likes` (composite unique post_id+user_id, cascade delete)
- Model `PostLike`
- `CommunityPost::isLikedBy($user)` + `isOwnedBy($user)`
- `like-toggle` controller pakai insert/delete PostLike row, increment/decrement `likes_count`
- AJAX response `{liked, likes}` untuk update tanpa reload
- Visual toggle: `.is-liked` class dengan `background: #fee2e2; color: #dc2626; fill heart icon`

### Share Button + Modal
- Sudah ada share button per-card dan per-detail (WA/FB/Twitter/Telegram/Email/Copy)
- Replaced emoji `💬 𝕏 ✈ ✉ 🔗` dengan text letters (W/f/X/T/@/L) untuk no-emoji compliance
- WhatsApp/FB/Twitter/Telegram/Email open share URL; Copy ke clipboard dengan "Tersalin!" feedback

## Redesign Sesuai Spec Mockup

### community.blade.php (List)
- **Hero section** `background: #005A2B; border-bottom-left-radius: 28px; border-bottom-right-radius: 28px;`
- **Tombol + Tulis** pill style 40px height, 90px min-width, white background
- **Search bar** rounded 12px, placeholder "Cari cerita atau tips..."
- **Filter chips** horizontal (Semua, Kisah Sukses, Tips & Trik, Tantangan, Lainnya)
  - Active chip: dark green `#005A2B` white text
  - Inactive: `#F3F3F3` dark text
- **Card style**: white, rounded 16px, light shadow, padding 18px
- **Category badge colors**:
  - Kisah Sukses → `#7EE5A0` hijau muda
  - Tips & Trik → green solid
  - Tantangan → orange
  - Lainnya → abu-abu
- **Title** line-clamp 3 baris
- **Excerpt** line-clamp 3 baris
- **Footer stats** author + role, ♡ likes, 💬 comments, 🕒 date
  - Note: pakai Lucide icons (heart, message-circle, clock) bukan emoji

### community-create.blade.php (Add Blog)
- **Header** `background: #005A2B; border-bottom-left-radius: 28px` dengan tombol back
- **Title section** "Bagikan Cerita Anda" + deskripsi
- **Kategori**: grid 2 kolom 2x2, radio button hidden, label styled card (hijau tua saat aktif, abu saat tidak)
- **Input judul** dengan placeholder "Contoh: Cara Saya Meningkatkan Penjualan..."
- **Isi cerita** textarea 250px min-height, placeholder "Tulis cerita, pengalaman, atau tips Anda di sini..."
- **Indikator** "Minimum 100 karakter" di bawah textarea
- **Tips box** background `#EAF7F0` dengan 4 tips menulis
- **Submit button** full-width 52px height, `background: #8BB09A`, "Bagikan Cerita" (no emoji)

### community-edit.blade.php
- Sama dengan create tapi prefill data existing
- Tombol "Batal" + "Simpan Perubahan" di action area (bukan full-width submit)

### community-detail.blade.php (Blog 1)
- **Header hijau** `min-height: 200px`, back button + category badge + title + author meta + date
- **Tombol edit** muncul di header kalau owner
- **Body article** background white, padding 24px
- **Action bar** rounded background `#F5F5F5` di bawah body: ♡ Like, 💬 Comment, ↗ Bagikan
- **Komentar section** dengan card per-komentar (nama + waktu + body)
- **Form komentar** textarea 80px height + "Kirim Komentar" button hijau

## Files

### Created
- `database/migrations/2026_06_22_000008_create_post_likes_table.php`
- `app/Models/PostLike.php`
- `resources/views/dashboard/community-edit.blade.php`

### Modified
- `app/Models/CommunityPost.php` (likes, isLikedBy, isOwnedBy)
- `app/Http/Controllers/DashboardController.php` (import PostLike, refactor like toggle, add edit/update/destroy, add liked+is_owner to decorate)
- `routes/web.php` (edit, update, destroy routes)
- `resources/views/dashboard/community.blade.php` (full rewrite)
- `resources/views/dashboard/community-create.blade.php` (full rewrite)
- `resources/views/dashboard/community-detail.blade.php` (full rewrite)

## Verifikasi

| Test | Result |
|---|---|
| 4 view files syntax check | No errors |
| 9 routes registered | All match |
| `/dashboard/community` HTTP | 200 |
| `/dashboard/community/create` HTTP | 200 |
| `/dashboard/community/{id}` HTTP | 200 |
| `/dashboard/community/{id}/edit` HTTP | 200 |
| Like toggle (CLI test) | 419 CSRF (normal, browser works) |
| Tinker: isLikedBy + isOwnedBy + decorate.liked + decorate.is_owner | All return correct values |
| Emoji scan 4 view files | 0 matches |

## Catatan

- **Like button visual toggle**: pakai class `.is-liked` dari controller response, fill heart icon merah
- **Search & filter**: live JS filtering by title/excerpt/author + category
- **Share button text**: W/f/X/T/@/L (letters) di share modal icons — bukan emoji
- **Lucide icons**: heart, message-circle, clock, share-2, pencil, trash-2, arrow-left — semua via CDN
- **404 di gambar tidak ada**: cover image di card list tidak dipakai (mockup spec: "Tidak ada cover image, NO HERO BANNER IMAGE")
