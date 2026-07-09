# Milestone 3 — Handoff

## Status: COMPLETE

## Files Created

### Migrations
- `2026_06_22_000004_create_plans_table.php` (composite unique on code+billing_period)
- `2026_06_22_000005_create_user_subscriptions_table.php`
- `2026_06_22_000006_create_marketplace_integrations_table.php`
- `2026_06_22_000007_create_carts_tables.php` (carts + cart_items)

### Models
- `app/Models/Plan.php` (with formatted_price, billing_label)
- `app/Models/UserSubscription.php` (with isLive, syncExpired, statusLabel)
- `app/Models/MarketplaceIntegration.php` (encrypted cast on api_key_encrypted, masked_key)
- `app/Models/Cart.php` (subtotal, totalQuantity, openFor)
- `app/Models/CartItem.php` (lineTotal, imageDataUri)

### Controllers
- `app/Http/Controllers/Admin/PlanManagementController.php`
- `app/Http/Controllers/Admin/AdminSubscriptionController.php`
- `app/Http/Controllers/SubscriptionController.php`
- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/MarketplaceController.php`

### Views
- `admin/plans/{index,create,edit,_form}.blade.php`
- `admin/subscriptions/{index,show}.blade.php`
- `subscription/{plans,my,checkout}.blade.php`
- `cart/{index,success}.blade.php`
- `marketplace/{index,connect}.blade.php`

### Seeders
- `database/seeders/PlanSeeder.php` (4 plans: app/marketplace × monthly/yearly)

## Files Modified
- `app/Models/User.php` (added subscriptions/activeSubscription/hasMarketplaceAccess/carts/marketplaceIntegrations/deactivateForExpiredSubscription)
- `app/Http/Controllers/AuthController.php` (sync expired subscriptions at login, deactivate user jika tidak ada sub aktif)
- `routes/web.php` (admin plans + subscriptions, user subscription/cart/marketplace routes)
- `resources/views/layouts/dashboard.blade.php` (menu Subscription, Keranjang, Marketplace)
- `database/seeders/DatabaseSeeder.php` (registered PlanSeeder)

## Bug Fixes During M3

1. **Migration plans unique conflict** — `code` saja unique menyebabkan conflict karena `app`+monthly dan `app`+yearly konflik. Fix: ubah unique jadi `['code','billing_period']` composite.
2. **PlanSeeder pertama fail** karena migration lama masih punya unique di `code` saja. Fix: rollback all 4 M3 migrations → re-migrate dengan schema baru → seed berhasil.

## Commands Run (exit 0)

| Command | Result |
|---|---|
| `php artisan migrate --force` (M3 batch) | 4 migrations DONE |
| `php artisan db:seed --class=PlanSeeder` | 4 plans inserted |
| `php -l` on 13 PHP files | No syntax errors |
| `php artisan route:list` | 24 new routes registered (7 plans+subs admin, 17 user subscription/cart/marketplace) |
| HTTP smoke test 8 routes | All 200 |
| End-to-end tinker test | Sub create/sync/deactivate, cart subtotal, marketplace encrypted/masked all pass |
| Emoji scan 14 view files | 0 matches |

## Demo Accounts Baru

Tidak ada user baru di M3. Existing accounts:
- `super.admin@bookify.com` / `admin12345` (admin)
- `user.demo@bookify.com` / `password123` (user, aktif)
- `user.expired@bookify.com` / `password123` (user, non-aktif, reason "Langganan berakhir")

## Test Flow yang Bisa Dicoba User

### Subscription Flow
1. Login `user.demo@bookify.com`
2. Buka `/subscription` → lihat 4 paket (app/marketplace × monthly/yearly)
3. Klik "Pilih Paket" → checkout view dengan tab E-Wallet/VA/QRIS
4. Pilih metode → klik "Bayar" → redirect ke `/subscription/my` dengan success message
5. Buka `/subscription/my` → lihat subscription aktif + history

### Cart Flow
1. Login `user.demo@bookify.com`
2. Buka `/cart`
3. Tambah item (nama, harga, jumlah, opsional gambar)
4. Update jumlah / hapus item
5. Checkout → success view dengan reference number

### Marketplace Flow
1. Login `user.demo@bookify.com`
2. Subscribe Marketplace plan dulu (lihat di atas)
3. Buka `/marketplace` → jika sub bukan marketplace, tampil locked banner
4. Setelah subscribe marketplace → `/marketplace/connect` → isi platform + API key
5. Kembali ke `/marketplace` → lihat card dengan masked API key
6. Toggle aktif / hapus

### Admin Flow
1. Login `super.admin@bookify.com`
2. Buka `/admin/plans` → list 4 plans, bisa edit/hapus/toggle
3. Buka `/admin/subscriptions` → monitor semua user subscriptions + filter by status

### Subscription Expiry Auto-Deactivate
1. Login `user.expired@bookify.com` → ditolak dengan reason "Langganan berakhir"
2. Coba subscribe di `/subscription` → reaktifasi akun otomatis saat pembayaran sukses

## Decisions Inside Scope

1. **Composite unique** di plans: `['code', 'billing_period']` karena business model perlu 2 plan dengan code sama tapi periode berbeda.
2. **API key encryption** pakai Laravel `encrypt()/decrypt()` via `Attribute` cast di `MarketplaceIntegration`. Hidden dari default serialization.
3. **Mock QRIS**: pakai `api.qrserver.com` untuk generate QR (online service, no Composer dependency). Payload simple string `BOOKIFY|planId|price|timestamp`.
4. **Login-time sync**: expired subscriptions di-check saat login (bukan scheduler) — sesuai requirement M1.
5. **Marketplace access gate**: `MarketplaceController::create` redirect ke plans jika user tidak punya marketplace plan. UI `/marketplace` tampil locked banner untuk non-marketplace users.
6. **Active sub cancel**: user dengan subscription aktif bisa cancel via `/subscription/my`. Status jadi cancelled, tidak ada auto-refund (mock).
7. **Cart checkout success view** simple: reference number + total. Tidak ada real payment flow (sama dengan subscription).

## Decisions Butuh Parent Approval

(tidak ada)

## Residual Risks

1. **`api.qrserver.com` external dependency** untuk QRIS. Jika offline, gambar QR tidak load. Alternatif: install `simplesoftwareio/simple-qrcode` (Composer) — belum dilakukan karena dependency minimal dan service gratis stabil.
2. **Login flow expired check** bisa di-bypass dengan `is_active` di-toggle manual via DB. Untuk production perlu scheduler + middleware check.
3. **Subscription metadata** berisi IP address — simulasi webhook, tidak ada signature verification.

## Test Contract (all passed)

1. `/admin/plans` 200 → tampil 4 plans seeded
2. `/admin/plans/create` 200 → form render
3. `/admin/subscriptions` 200 → list page
4. `/subscription` 200 → 4 plan cards
5. `/subscription/my` 200 → history list (kosong)
6. `/subscription/1/checkout` 200 → checkout view dengan payment tabs
7. `/cart` 200 → cart + add form
8. `/marketplace` 200 → locked banner (user.demo belum subscribe marketplace)
9. Tinker: create sub → set ends_at=kemarin → syncExpired → status=expired → user.is_active=false
10. Tinker: create integration dengan API key "super-secret-api-key-1234567890" → masked = "sup****890" + decrypt match = true
11. Cart: tambah item qty=2 price=50000 → subtotal=100000

## Complete Feature Status

| Modul | Status |
|---|---|
| Admin Dashboard + role + is_active | M1 ✓ |
| User CRUD + management | M2 ✓ |
| Article CRUD + thumbnail + user-facing read | M2 ✓ |
| Community image upload | M2 ✓ |
| Subscription plans + checkout mock | M3 ✓ |
| Subscription expiry auto-deactivate | M3 ✓ |
| Cart + checkout | M3 ✓ |
| Marketplace integration (UI + encrypted) | M3 ✓ |
| Admin plans/subscriptions monitor | M3 ✓ |
| Login guard for inactive | M1+M3 ✓ |
| No emoji, Lucide icons | All milestones ✓ |
