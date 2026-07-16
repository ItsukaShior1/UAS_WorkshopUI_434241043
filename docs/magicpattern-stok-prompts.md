# MagicPattern Wireframe Prompt — Halaman Stok (Bookify UI)

> File khusus halaman **Stok User**: List, Detail, dan Form Tambah/Edit Produk.
> Pakai bersama style block grayscale di `magicpattern-wireframe-prompt.md`.

---

## 🎨 Style Block (WAJIB — Tempel di awal setiap prompt)

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
No real icons, no illustrations, no icon set.
Typography: a single sans-serif font (Inter or system sans).
Stroke style: 1px solid #111 for borders.
Wireframe look — think Balsamiq / wireframe.cc aesthetic.
```

---

## 🧩 PROMPT A — Halaman Stok (List Produk / Inventori)

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
No real icons, no illustrations, no icon set.
Typography: a single sans-serif font (Inter or system sans).
Stroke style: 1px solid #111 for borders.
Wireframe look — think Balsamiq / wireframe.cc aesthetic.

Generate a single responsive web page wireframe titled "Stok Produk"
for an Indonesian inventory app called "Bookify".

Top section:
- Navbar with logo placeholder (left, square 40x40), page title text
  bar in center, and user avatar circle placeholder (right, 36px circle).

Page header area (below navbar):
- Title "Manajemen Stok" (large bold bar, 32px equivalent)
- Description bar (1 line, smaller, mid gray)
- Right side: 1 primary button "Tambah Produk" (filled black, white text bar)

Stat row (4 cards horizontal, equal width):
1. "Total Produk" — large number bar + small label bar
2. "Stok Kritis" — large number bar + small label bar (top border #111)
3. "Stok Rendah" — large number bar + small label bar
4. "Total Aset" — large Rp-format number bar + small label bar

Filter bar (white card with border):
- 1 text input (search, grow): placeholder "Cari produk..."
- 1 dropdown select: "Filter Status" with 4 options label bars:
  Semua / Aman / Rendah / Kritis
- 1 button "Filter" (outlined, black border)

Main content: card containing a TABLE placeholder.
Columns (6):
1. No (small box ~30px)
2. Produk (image square 40x40 + 2 stacked text bars = name + category)
3. Stok Saat Ini (number bar + horizontal progress bar placeholder below,
   filled to ~60% with #4B5563)
4. Stok Minimum (number bar)
5. Status (pill placeholder: rounded rect with light gray bg, label bar
   inside; pills differ only in border weight, NOT color)
6. Aksi (3 small square icon placeholders in a row: edit, history, delete)

Show 6 rows of placeholder data. Row height ~64px.
Add horizontal scroll indicator (thin gray bar with right-arrow square)
at right edge to signal overflow on narrow screens.

Empty state (shown if no rows): dashed border box (centered in table area)
with:
- icon placeholder square (48x48, mid gray) centered
- 2 stacked text bars (heading + sub)
- 1 primary button "Tambah Produk Pertama"

Bottom: pagination placeholder (4 small boxes: prev, 1, 2, 3, next).

Responsive behavior under 720px:
- Stat row wraps to 2x2 grid
- Filter bar stacks vertically (full width each)
- Table becomes VERTICAL CARD LIST:
  each row = full-width card with stacked rows of (label bar + value bar)
- Sticky bottom action bar appears with "Tambah Produk" button (full width)

Pure grayscale wireframe, no icons, no real images.
```

---

## 🧩 PROMPT B — Halaman Detail Stok (Stock Detail)

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
No real icons, no illustrations, no icon set.
Typography: a single sans-serif font (Inter or system sans).
Stroke style: 1px solid #111 for borders.
Wireframe look — think Balsamiq / wireframe.cc aesthetic.

Generate a single responsive web page wireframe titled "Detail Produk"
for an Indonesian inventory app called "Bookify".

Top: navbar (same as List page) + breadcrumb bar:
  "Stok / [Nama Produk]" (smaller text bars, separator = "/").

Two-column layout (gap 24px):

LEFT COLUMN (40% width) — card containing:
- Large image placeholder square (240x240, light gray bg, centered icon
  placeholder square inside)
- Thumbnail row below: 4 small squares (60x60 each) with the first one
  having a thicker black border (active state)
- Product name (large bold text bar, 20px equivalent)
- Category pill (small rounded rect, light gray bg) + price (large bar)
- Status badge pill: "Stok Aman / Rendah / Kritis" — pill height 24px,
  border style indicates level
- Two stat boxes side-by-side (each = label bar + big number bar):
  - "Stok Saat Ini"
  - "Stok Minimum"
- Progress bar placeholder (full width rect, 12px tall) labeled
  "Indikator Stok" — fill bar inside filled to ~70% with solid #4B5563
- 2 action buttons row:
  - "Edit Produk" (primary, filled black)
  - "Hapus" (secondary, outlined black)
  Right-aligned, equal width.

RIGHT COLUMN (60% width) — card containing:

Section "Ringkasan Aktivitas":
- 4 mini-stats in horizontal row (each = label bar + value bar):
  - Total Transaksi
  - Stok Keluar (jumlah)
  - Stok Masuk (jumlah)
  - Nilai Perputaran (Rp)

Section divider (1px line).

Tab bar below (2 tabs, equal width):
- Tab 1: "Riwayat Transaksi" (ACTIVE: bold text bar + 2px solid black
  underline)
- Tab 2: "Statistik" (inactive: mid gray text bar, no underline)

Active tab content: TABLE placeholder, 6 columns:
1. Tanggal (2-line: date + time bars)
2. Tipe (pill: "Penjualan" or "Restock", border style differs)
3. Jumlah (number bar)
4. Harga Satuan (Rp-format number bar)
5. Total (Rp-format number bar, slightly bolder)
6. Catatan (1-line text bar, truncated)

Show 5 rows of placeholder data.
Alternating row background: row 1,3,5 = #FFFFFF; row 2,4 = #F9FAFB.

Below table: small pagination placeholder (3 boxes).

Empty state for history (shown if no rows):
- dashed border box (centered in table area)
- icon placeholder square (centered)
- 2 stacked text bars
- 1 button "Catat Transaksi Pertama"

Responsive behavior under 720px:
- 2 columns collapse to 1 column, image card moves to TOP
- Right column flows below
- Tabs remain side-by-side but shrink
- Stat row wraps 2x2

Pure grayscale wireframe, no icons, no real images.
```

---

## 🧩 PROMPT C — Form Tambah / Edit Produk

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
No real icons, no illustrations, no icon set.
Typography: a single sans-serif font (Inter or system sans).
Stroke style: 1px solid #111 for borders.
Wireframe look — think Balsamiq / wireframe.cc aesthetic.

Generate a single responsive web page wireframe titled "Form Produk"
for an Indonesian inventory app called "Bookify".

Top: navbar (same as List page) + breadcrumb bar:
  "Stok / Tambah Produk Baru"  OR  "Stok / Edit Produk"

Page header:
- Title: "Tambah Produk Baru" or "Edit Produk" (large bold bar, 28px)
- Right side: 1 secondary button "Kembali" (outlined, with left-arrow
  square placeholder)

Single-column form card (max width 720px, centered on page, white bg,
1px border, 16px corner radius):

SECTION 1 — "Informasi Produk" (small section heading bar + 16px gap):
- Input "Nama Produk" (full-width text input, ~48px tall)
- Input "Kategori" (full-width text input)
- Input "Harga" (number input with "Rp" prefix placeholder on left side
  inside the input box)

SECTION 2 — "Gambar Produk" (heading bar + 16px gap):
- Large drop-zone rectangle (full-width, 300px tall, dashed 2px black
  border, light gray bg):
  - icon placeholder square (48x48, centered)
  - text bar "Tarik gambar ke sini atau klik untuk upload" (centered)
  - small text bar "PNG/JPG, maks 2MB" (centered, mid gray)
- Thumbnail preview row (only shown when image uploaded):
  3 small squares (80x80 each) side-by-side, each with a small "X" square
  button at top-right corner.

SECTION 3 — "Manajemen Stok" (heading bar + 16px gap):
- Two inputs side-by-side (gap 12px):
  - "Stok Awal" (number input)
  - "Stok Minimum" (number input)
- Helper text bar below each (mid gray, 12px equivalent):
  "Jumlah unit tersedia saat ini" / "Batas minimum untuk peringatan"

SECTION 4 — "Catatan" (heading bar + 16px gap):
- Textarea (4 rows tall, full-width, with placeholder text bar inside
  showing "Tambahkan catatan opsional...")

Form validation hints (where applicable):
- Small text bar below invalid field, bordered with 1px #4B5563 (since
  red not allowed, use darker gray + slightly thicker to indicate error)

Bottom action bar (white bg, 1px top border, 16px vertical padding):
- STICKY on mobile (fixed to bottom of viewport)
- Inside: flex row with 2 buttons aligned to RIGHT:
  - Secondary "Batal" (outlined black, 120px wide)
  - Primary "Simpan Produk" (filled black, 160px wide)
- 16px gap between buttons

Responsive behavior under 720px:
- All sections become single-column (no side-by-side inputs)
- Section headings remain left-aligned
- Sticky bottom action bar visible with full-width buttons
- Form card has 16px side padding (instead of 28px)

Pure grayscale, no icons, no images, no real colors.
```

---

## 🛠️ CARA PAKAI

1. Buka https://www.magicpattern.design
2. Pilih **Wireframes** atau **Generate**
3. Tempel **Style Block** + salah satu Prompt (A / B / C)
4. Generate → Download SVG → Import ke Figma (File → Import)
5. SVG jadi vector frame editable

## 💡 TIPS KHUSUS STOK

- Karena halaman Stok banyak **tabel & form**, tambahkan kata kunci:
  `"spreadsheet-like table wireframe with light gridlines"` agar MagicPattern
  render grid lebih jelas.
- Untuk tombol aksi di tabel (edit/hapus), pakai placeholder kotak kecil —
  MagicPattern tidak render icon, jadi cukup kotak dengan border.
- Untuk drop-zone upload, pakai dashed border yang tebal — ini ciri khas
  wireframe upload area.
- Kalau ingin ada versi mobile juga, tambahkan di akhir prompt:
  `"Also generate a 720px-wide responsive variant side-by-side"`.