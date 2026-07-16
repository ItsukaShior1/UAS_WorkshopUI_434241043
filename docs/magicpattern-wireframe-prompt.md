# MagicPattern Wireframe Prompt — Bookify UI

> Gunakan prompt ini di MagicPattern (https://www.magicpattern.design) untuk generate wireframe grayscale (hitam-putih/abu-abu) yang siap di-export ke Figma.

---

## 🎯 Instruksi Cepat

1. Buka https://www.magicpattern.design → pilih **Wireframes** atau **Generate**.
2. Tempel salah satu prompt di bawah sesuai halaman yang ingin di-generate.
3. Tambahkan constraint: **grayscale only, no images, no icons, monochrome**.
4. Export → **SVG** atau **PNG** → Import ke Figma.

---

## 📦 GAYA WIREFRAME (WAJIB — Tempel di awal setiap prompt)

```
Style: low-fidelity grayscale wireframe ONLY.
Color palette: ONLY pure white #FFFFFF, light gray #E5E7EB, mid gray #9CA3AF,
dark gray #4B5563, and pure black #111111.
No colored accents. No gradients. No shadows. No brand colors.
Use only simple geometric placeholders:
- Rectangle boxes for images
- Lines for text (gray bars)
- Circles for avatars
- Plain buttons (rounded rectangle with black border)
No real icons, no illustrations, no icons set.
Typography: a single sans-serif font (Inter or system sans).
Stroke style: 1px solid #111 for borders.
Wireframe look — think Balsamiq / wireframe.cc aesthetic.
```

---

## 🧩 PROMPT 1 — Halaman Subscription / Plans (Paket Langganan)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Subscription Plans"
for an Indonesian SaaS app called "Bookify".

Top: navbar with logo placeholder (left) and 2 menu links (right).
Below: page heading "Pilih Paket Langganan" with a sub-line.
Banner: a wide placeholder rectangle labeled "Upgrade Banner" with
3 bullet lines below describing discount 30%.
Main content: a 2-column responsive grid of pricing cards.
Each card contains:
- plan name (text line)
- "30% OFF" tag (small box, top-right)
- original price (strikethrough text bar)
- discounted price (large text bar)
- 4 bullet feature rows (small gray bars)
- 2 buttons stacked: primary "Pilih Paket" (black) and secondary
  "Detail" (white outlined).
Footer: 3-column placeholder footer with text bars.

Add a small badge group at top-right showing toggle:
"Bulanan | Tahunan".
Layout must be responsive — cards stack to 1 column under 720px.
Single-page wireframe, no real images, no icons, only rectangles and lines.
```

---

## 🧩 PROMPT 2 — Halaman Checkout (Subscription Checkout)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Checkout Langganan".

Top: simple navbar (logo placeholder + back link).
Two-column layout:
LEFT (60%):
- section "Detail Pesanan" with 1 line item: package name, period,
  original price (strikethrough bar), discount badge, final price.
- section "Informasi Pembayaran" with 3 stacked input fields:
  nama, email, no HP — all labeled with text bar above each.
- radio option group: 2 options (QRIS, Transfer Bank).

RIGHT (40%):
- card "Ringkasan Pembayaran" with:
  - subtotal row (label + amount)
  - diskon row (label + amount)
  - total row (larger bar, bolder)
  - 1 primary button "Bayar Sekarang"
  - small text bar "Dengan melanjutkan, Anda menyetujui S&K"

Bottom: footer placeholder.

Responsive: collapses to single column under 720px, summary card moves
to top. Grayscale only. No icons, no images.
```

---

## 🧩 PROMPT 3 — Halaman Cart / Keranjang

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Keranjang".

Top: navbar with logo + cart count badge.
Two-column layout:
LEFT (65%):
- list of 3 cart items. Each item = horizontal card with:
  - left: image placeholder (gray square 80x80)
  - middle: 2 text bars (title + meta)
  - right: price text bar + quantity stepper (- 1 +) + remove link
- empty state placeholder shown if list empty.

RIGHT (35%):
- card "Ringkasan Belanja" with:
  - subtotal row
  - diskon row
  - total row (bold, larger bar)
  - 1 button "Lanjut ke Pembayaran"
  - 1 secondary button "Kembali Belanja"

Bottom: footer.

Responsive under 720px: right column moves to bottom.
Pure grayscale, no icons, simple boxes only.
```

---

## 🧩 PROMPT 4 — Halaman My Subscription (Langganan Saya)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Langganan Saya".

Top: navbar with logo + user avatar placeholder.
Page header: title + 2 link buttons on the right ("Riwayat Pembayaran",
"Lihat Paket").

Main content (vertical stack):
1. Active plan card (highlighted with thicker black border):
   - plan name + status tag "Aktif"
   - period + start/end date (text bars)
   - progress bar placeholder (rectangle) labeled "Sisa waktu langganan"
   - 3 action buttons in a row: "Perpanjang", "Upgrade", "Batalkan"
2. Benefits section: 3-column grid of feature cards (icon placeholder
   square + 2 text bars each).
3. Billing history mini-section: 3 rows of placeholder list with
   date, item, amount, status pill.
4. Side-by-side comparison table at bottom: 2 columns (App vs Marketplace)
   with checkmark/cross placeholders per row.

Responsive: stack everything to 1 column under 720px.
Grayscale, no icons, no images.
```

---

## 🧩 PROMPT 5 — Halaman Riwayat Pembayaran (User)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Riwayat Pembayaran".

Top: navbar.
Page header: title + small description bar.

Stats row: 6 stat cards in a horizontal row (each = label bar + value bar).
Each card has thin top border (different widths, all gray).

Below: filter bar (1 dropdown + 1 button "Filter") on left.

Main list: vertical list of 5 payment items.
Each item = horizontal card:
- left: small circle placeholder (status indicator, black/gray)
- middle: title bar + meta line (date, reference) + 1-2 note bars
  (gray background blocks)
- right: amount bar + optional refund amount bar (smaller, below)

Empty state at bottom: dashed border box with icon placeholder square +
2 text bars + 1 button.

Pagination placeholder at very bottom (4 boxes).

Responsive: stat row wraps to 2x3, list items collapse to 2-column grid.
Pure grayscale.
```

---

## 🧩 PROMPT 6 — Admin Dashboard + Admin Transaksi

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Admin - Transaksi".

Top: admin header with logo + admin avatar placeholder + logout link.

Left sidebar (240px): vertical list of 6 menu items, with the 3rd item
("Transaksi") highlighted/active (darker background).
Menu items: Dashboard, Pengguna, Paket, Transaksi, Pengaturan, Logout.

Main content area (right of sidebar):
- page header: "Transaksi Pembayaran Langganan" + description bar.
- stats grid: 4 stat cards in a row (label + value + small hint bar).
- filter bar: 1 text input (grow) + 1 dropdown + 1 button.
- table placeholder: 7 columns
  (Tanggal, Pengguna, Paket, Referensi, Jumlah, Status, Aksi).
  Show 6 rows of placeholder data (gray bars).
  Each row has 1 action icon placeholder (small square) at right.
- pagination at bottom.

Responsive: sidebar collapses to top hamburger; table can stay scrollable
horizontally with gray overflow indicator.

Pure grayscale, no icons, simple rectangles.
```

---

## 🧩 PROMPT 7 — User Halaman Stok (List Produk / Inventori)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Stok Produk".

Top: navbar with logo placeholder (left), user avatar (right), and a
search input on top center.

Page header:
- title "Manajemen Stok"
- description bar (1 line)
- right side: 1 primary button "Tambah Produk"

Stat row: 4 stat cards horizontal:
- Total Produk
- Stok Kritis
- Stok Rendah
- Total Aset (Rp placeholder)

Filter bar: 1 text input (search) + 1 dropdown (filter status:
Semua / Aman / Rendah / Kritis) + 1 button "Filter".

Main content: card containing a TABLE placeholder.
Columns (6):
1. No (small box)
2. Produk (image square 40x40 + 2 text bars = name + category)
3. Stok Saat Ini (number + small progress bar placeholder below)
4. Stok Minimum (number)
5. Status (pill placeholder: "Aman" / "Rendah" / "Kritis")
6. Aksi (3 icon placeholders in a row: edit, history, delete)

Show 6 rows of placeholder data.
Add horizontal scroll indicator (gray bar) on the right edge to indicate
responsive overflow.

Empty state: dashed border box centered with icon square + 2 text bars
+ 1 primary button "Tambah Produk Pertama".

Bottom: pagination placeholder (4 boxes).

Responsive under 720px:
- stat row wraps to 2x2
- table becomes vertical card list (each row = stacked label + value)
- sticky bottom action bar with "Tambah Produk" button

Pure grayscale wireframe, no icons, no real images.
```

---

## 🧩 PROMPT 8 — User Halaman Detail Stok (Stock Detail)

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Detail Produk".

Top: navbar + breadcrumb bar ("Stok / [Nama Produk]").

Two-column layout:
LEFT (40%):
- card with:
  - large image placeholder square (240x240)
  - thumbnail row (4 small squares below)
  - product name (large bar)
  - category pill + price (large bar)
  - status badge "Stok Aman / Rendah / Kritis" (pill)
  - 2 stat boxes side-by-side:
    - "Stok Saat Ini" (big number bar + label)
    - "Stok Minimum" (big number bar + label)
  - progress bar placeholder labeled "Indikator Stok" (rectangle, with
    fill in solid #4B5563)
  - 2 action buttons row: "Edit Produk" + "Hapus"

RIGHT (60%):
- card "Ringkasan Aktivitas" with 4 mini-stats:
  - Total Transaksi
  - Stok Keluar (jumlah)
  - Stok Masuk (jumlah)
  - Nilai Perputaran (Rp bar)

Below: tab bar with 2 tabs ("Riwayat Transaksi" active, "Statistik").
Active tab underline = solid black line.

Active tab content: TABLE placeholder with columns:
- Tanggal
- Tipe (pill: "Penjualan" / "Restock")
- Jumlah
- Harga Satuan
- Total
- Catatan

Show 5 rows of placeholder data, alternating row background (#F9FAFB /
#FFFFFF).

Below table: small pagination.

Empty state for history: dashed box + icon placeholder + 2 text bars.

Responsive under 720px: 2 columns collapse to 1, image card moves on
top. Pure grayscale wireframe, no icons.
```

---

## 🧩 PROMPT 9 — User Form Tambah/Edit Produk

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a single responsive web page wireframe titled "Form Produk".

Top: navbar + breadcrumb ("Stok / Tambah Produk" or "Stok / Edit Produk").

Page header: title "Tambah Produk Baru" or "Edit Produk" + 1 secondary
button "Kembali" on the right.

Single-column form card (max width 720px, centered):
- section 1 "Informasi Produk":
  - input "Nama Produk" (text input, full width)
  - input "Kategori" (text input)
  - input "Harga" (number input with "Rp" prefix placeholder)
- section 2 "Gambar Produk":
  - large drop zone (dashed border rectangle, 300px tall) with:
    - icon placeholder square (centered)
    - text bar "Tarik gambar ke sini atau klik untuk upload"
    - small text bar "PNG/JPG, maks 2MB"
  - thumbnail preview row (3 small squares) with X button placeholder
- section 3 "Manajemen Stok":
  - 2 inputs side-by-side: "Stok Awal" + "Stok Minimum"
  - helper text bar below each
- section 4 "Catatan" (optional):
  - textarea (4 rows tall)

Bottom action bar (sticky on mobile):
- left: secondary button "Batal"
- right: primary button "Simpan Produk" (filled black)

Form validation hints: small red-tinted text bar (use mid gray here, no
red color allowed) under invalid fields.

Responsive under 720px: 2-column inputs collapse to 1 column, sticky
bottom action bar visible.

Pure grayscale, no icons, no images.
```

---

## 🧩 PROMPT 10 — Admin Cancel/Refund Modal

```
[PASTE GAYA WIREFRAME DI ATAS]

Generate a wireframe of a modal dialog titled "Batalkan / Refund Pembayaran".

Modal sits over a dimmed page background (60% black overlay).

Modal content (centered, ~520px wide):
- title bar + close button (X placeholder, top-right)
- info row: payment ID + status pill placeholder
- divider line
- form fields stacked:
  - 1 textarea (3 rows tall) labeled "Alasan pembatalan"
  - 1 toggle row: "Sertakan refund" (switch placeholder + label)
  - 1 number input labeled "Nominal refund"
- 2 buttons at bottom-right: secondary "Batal" + primary "Proses
  Pembatalan" (filled black).

Pure grayscale, no icons, no real images.
```

---

1. Di MagicPattern, klik **Download** → pilih **SVG** (best for Figma) atau **PNG**.
2. Buka Figma → **File → Import**.
3. Pilih file hasil download.
4. SVG akan terimport sebagai **vector frame** yang bisa di-edit langsung.
5. Susun semua halaman jadi 1 Figma file dengan **Frames** terpisah per halaman.

---

## 💡 TIPS TAMBAHAN

- Kalau hasil MagicPattern terlalu "desain", tambahkan kata kunci:
  `"Balsamiq-style"`, `"hand-drawn low-fi"`, `"sketch wireframe"`.
- Kalau ingin benar-benar hanya garis-garis, tambahkan:
  `"Only outlines, no fills except pure white. Pure black 1px strokes only."`
- Generate per-halaman, bukan satu URL panjang — kualitas lebih konsisten.
- Tambahkan `"include responsive view at 720px width"` jika ingin
  dapat 2 versi (desktop + mobile) sekaligus.