# Milestone 2 — Handoff

## Status: COMPLETE

## Files Changed

### Created
- `database/migrations/2026_06_22_000002_add_image_to_community_posts.php`
- `database/migrations/2026_06_22_000003_create_articles_table.php`
- `database/seeders/DemoUserSeeder.php`
- `app/Models/Article.php`
- `app/Http/Controllers/Admin/UserManagementController.php`
- `app/Http/Controllers/Admin/ArticleManagementController.php`
- `app/Http/Controllers/ArticleController.php`
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/articles/index.blade.php`
- `resources/views/admin/articles/create.blade.php`
- `resources/views/admin/articles/edit.blade.php`
- `resources/views/articles/index.blade.php`
- `resources/views/articles/show.blade.php`

### Modified
- `app/Models/User.php` (added posts/articles relations)
- `app/Models/CommunityPost.php` (added image_data/image_mime fillable + imageDataUri accessor)
- `app/Http/Controllers/DashboardController.php` (communityStore handles base64 image)
- `resources/views/dashboard/community-create.blade.php` (image upload input + preview JS, removed emoji)
- `resources/views/layouts/dashboard.blade.php` (added "Artikel" nav, replaced emoji icons with Lucide)
- `routes/web.php` (admin/users/* + admin/articles/* + public articles routes)
- `database/seeders/DatabaseSeeder.php` (registered DemoUserSeeder)
- `resources/views/admin/layout.blade.php` (FIX: title section, replaced @yield-as-arg with $__env->yieldContent)

## Bug Fixes During M2

1. **`admin/layout.blade.php` ParseError** — `@yield('page-title', 'X')` di dalam argumen `@section('title', ...)` invalid. Fix: pakai `$__env->yieldContent('page-title') ?: 'X'`.
2. **`layouts/dashboard.blade.php` Undefined $user** di `/articles` — sidebar profile pakai `$user->name` padahal tidak di-pass dari `ArticleController@index`. Fix: ganti ke `auth()->user()->name`.
3. **Emoji cleanup** di `community-create.blade.php` (💡 Tips Menulis, ✈ Bagikan) dan `layouts/dashboard.blade.php` (☰ hamburger, 🔔 bell) → diganti Lucide icons + text label.

## Commands Run (exit 0 semua)

| Command | Result |
|---|---|
| `php artisan migrate --force` | 2 migrations DONE |
| `php artisan db:seed --class=DemoUserSeeder` | 2 users seeded |
| `php artisan route:list` | All admin/articles routes registered |
| `php -l` on all PHP files | No syntax errors |
| HTTP smoke test 7 routes | 6x 200, 1x 403 (non-admin→/admin) |
| Emoji scan (regex) on 13 view files | 0 matches |
| Article CRUD test (tinker) | create/slug-collision/thumbnail/toggle/delete all OK |

## Demo Accounts Baru

| Email | Password | Role | Status |
|---|---|---|---|
| `user.demo@bookify.com` | `password123` | user | Aktif |
| `user.expired@bookify.com` | `password123` | user | Non-Aktif (alasan: "Langganan berakhir") |

## Decisions Inside Scope

1. **Article thumbnail**: base64 + mime (konsisten pattern Bookify existing untuk cover image).
2. **Slug uniqueness**: `Article::generateUniqueSlug` dengan suffix `-2`, `-3`, dst.
3. **User search/filter**: di `/admin/users` pakai query string `?q=...&status=active|inactive|admin|user`.
4. **Article filter**: `?q=...&status=published|draft`.
5. **Admin tidak bisa hapus/disable akun sendiri**: enforced di controller + edit form disable field.
6. **User dengan posts/articles tidak dihapus total**, hanya di-deactivate (data integrity).
7. **Community image**: max 2MB, mime image/jpeg|png|webp. `cover_url` auto-set dari image_data URI agar list view (existing) tetap render tanpa edit.
8. **Artikel user-facing read** = `ArticleController` (terpisah dari `community_posts`).
9. **Sidebar nav "Artikel"** pakai icon community.png yang sudah ada (visual consistency dengan menu Komunitas).

## Decisions Butuh Parent Approval

(tidak ada)

## Residual Risks

1. **Defensive `$errors` di form views** — smoke test render manual dapat `Undefined $errors`, tapi di real request Laravel auto-share `ViewErrorBag`. Aman di runtime.
2. **Notifikasi bell icon** — visualnya berbeda dari versi emoji, mungkin user notice.
3. **User.expired@bookify.com** — saat ini `is_active=false`, jadi tidak bisa login sama sekali. Ini sesuai definisi M1 (deactive → tolak login). Test flow nanti di M3.

## Validation Contract (all passed)

1. Login admin → `/admin/users` tampil table
2. Login admin → `/admin/users/create` tampil form
3. Login admin → `/admin/articles/create` tampil form dengan thumbnail upload preview JS
4. Login user → `/articles` tampil (kosong karena belum ada published)
5. Login user → `/admin/users` → 403
6. Article create dengan judul sama → slug auto suffix
7. Article thumbnail base64 → access `thumbnail_data_uri` → return `data:image/jpeg;base64,...`
8. Tidak ada emoji UTF-8 di 13 view files

## Next: Milestone 3

- Migrations: plans, user_subscriptions, marketplace_integrations, carts, cart_items
- Models: Plan, UserSubscription, MarketplaceIntegration, Cart, CartItem (encrypted casts)
- `PlanSeeder` (App + Marketplace, harga Rp 49k/99k)
- `Admin/PlanManagementController` CRUD + toggle-active
- `Admin/AdminSubscriptionController` monitor index + show
- User-facing: `SubscriptionController` (plans/my/cancel/checkout/confirm), `CartController` (CRUD + checkout), `MarketplaceController` (connect form + encrypted store)
- Views: `subscription/{plans,my,checkout}`, `cart/index`, `marketplace/{connect,index}`, `admin/plans/*`, `admin/subscriptions/*`
- Update `layouts/dashboard.blade.php`: menu Langganan, Keranjang, Marketplace (conditional per active plan)
- Composer: `simplesoftwareio/simple-qrcode` untuk QRIS generator
- Checkout mock Midtrans: tab E-Wallet/VA/QRIS, QR generator render QR
- Login guard: cek subscription expiry → `is_active=false` + reason "Langganan berakhir"
