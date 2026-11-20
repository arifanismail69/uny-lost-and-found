# 01 - Design Tokens

Sumber: `aplikasi/public/css/tokens.css` (CSS variables). Nilai diambil dari wireframe dan prototipe Pertemuan 3: abu-abu dengan satu warna aksen biru, serta tiga warna status.

## Warna

| Token | Nilai Kelompok | Contoh Penggunaan |
|---|---|---|
| `--color-primary` | `#1d4ed8` | Tombol utama, tautan, menu aktif |
| `--color-primary-hover` | `#1e40af` | Hover tombol utama |
| `--color-primary-soft` | `#dbeafe` | Latar menu aktif, chip filter, kartu terpilih |
| `--color-admin` | `#1e3a8a` | Header layar petugas (pembeda peran) |
| `--color-text` | `#111827` | Judul dan isi halaman |
| `--color-text-muted` | `#4b5563` | Teks bantuan, meta kartu |
| `--color-surface` | `#ffffff` | Latar kartu, panel, field |
| `--color-bg` | `#f3f4f6` | Latar halaman |
| `--color-border` | `#d1d5db` | Garis tepi |
| `--color-success` / `-soft` | `#166534` / `#dcfce7` | Terverifikasi, Disetujui, Dapat diklaim |
| `--color-warning` / `-soft` | `#92400e` / `#fef3c7` | Menunggu, peringatan ciri rahasia |
| `--color-danger` / `-soft` | `#b91c1c` / `#fee2e2` | Galat, Ditolak, tombol Tolak |
| `--color-neutral-soft` | `#e5e7eb` | Sudah diklaim, tombol nonaktif |

## Tipografi

| Token | Nilai | Penggunaan |
|---|---|---|
| `--font-family` | Segoe UI, system-ui, Roboto, Arial | Seluruh teks |
| `--font-size-heading` | 1.75rem (28px), 1.5rem di mobile | Judul halaman |
| `--font-size-subheading` | 1.125rem (18px) | Subjudul, judul kartu |
| `--font-size-body` | 1rem (16px) | Isi |
| `--font-size-label` | 0.9375rem (15px) | Label field, tombol |
| `--font-size-caption` | 0.8125rem (13px) | Teks bantuan, badge, pesan galat |

## Jarak, bentuk, dan lain-lain

| Token | Nilai | Penggunaan |
|---|---|---|
| `--space-1` s.d. `--space-6` | 4, 8, 12, 16, 24, 32 px | Semua margin, padding, gap |
| `--radius-control` | 6px | Tombol, field |
| `--radius-card` | 10px | Kartu, panel |
| `--radius-pill` | 999px | Badge, chip, menu |
| `--shadow-card` | `0 1px 2px rgba(17,24,39,.08)` | Kartu |
| `--focus-ring` | 3px biru transparan | Fokus keyboard |
| `--touch-min` | 44px | Tinggi minimum tombol dan field |
| `--container-max` | 1120px | Lebar konten maksimum |

## Aturan pemakaian

- File CSS lain hanya memakai `var(--...)`; tidak menulis warna atau jarak sendiri.
- Status selalu berupa teks di dalam badge, warna hanya penguat.
- Perkiraan kontras (dihitung manual, belum diuji dengan alat): teks utama di atas putih sekitar 17:1, `--color-text-muted` sekitar 7:1, teks status di atas latar lembutnya sekitar 5-7:1. Semuanya di atas batas 4.5:1.
