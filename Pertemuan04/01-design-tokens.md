# 01 - Design Tokens

Sumber: `aplikasi/public/css/tokens.css` (CSS variables). Struktur dan hierarki mengikuti wireframe Pertemuan 3. Warna memakai identitas kampus UNY: biru tua dan kuning emas, serta tiga warna status.

## Warna

| Token | Nilai | Contoh Penggunaan |
|---|---|---|
| `--color-primary` | `#0f3a8a` | Tombol utama, tautan |
| `--color-primary-hover` | `#0b2c6b` | Hover tombol utama |
| `--color-primary-soft` | `#e3ebfa` | Latar ikon, pilihan terpilih |
| `--color-primary-deep` | `#0a2350` | Header, banner beranda, footer |
| `--color-accent` | `#f2b705` | Garis penanda menu aktif, tombol "Lapor Temuan", nomor langkah |
| `--color-accent-soft` | `#fff4cc` | Latar ikon aksen |
| `--color-on-accent` | `#1f2937` | Teks di atas kuning |
| `--color-text` | `#0f172a` | Judul dan isi |
| `--color-text-muted` | `#475569` | Teks bantuan, meta kartu |
| `--color-bg` | `#f5f7fb` | Latar halaman |
| `--color-surface` | `#ffffff` | Kartu, panel, field |
| `--color-border` | `#dbe2ee` | Garis tepi |
| `--color-success` / `-soft` | `#166534` / `#dcfce7` | Terverifikasi, Disetujui, Dapat diklaim |
| `--color-warning` / `-soft` | `#92400e` / `#fef3c7` | Menunggu, peringatan ciri rahasia |
| `--color-danger` / `-soft` | `#b91c1c` / `#fee2e2` | Galat, Ditolak, tombol Tolak |

### Kontras (diukur di browser dengan rumus WCAG)

| Pasangan | Rasio |
|---|---|
| Teks gelap di atas kuning | 8.07 : 1 |
| Putih di atas biru utama | 10.54 : 1 |
| Teks menu di header | 12.10 : 1 |
| Kuning di header | 8.44 : 1 |
| Teks muted di latar halaman | 7.07 : 1 |

Semua di atas batas 4.5 : 1.

## Tipografi

| Token | Nilai | Penggunaan |
|---|---|---|
| `--font-family` | Plus Jakarta Sans (Google Fonts), cadangan Segoe UI / system-ui | Seluruh teks |
| `--font-size-display` | 2.25rem (36px), 1.75rem di mobile | Judul banner beranda |
| `--font-size-heading` | 1.75rem (28px), 1.5rem di mobile | Judul halaman |
| `--font-size-subheading` | 1.125rem (18px) | Subjudul, judul kartu |
| `--font-size-body` | 1rem (16px) | Isi |
| `--font-size-label` | 0.9375rem (15px) | Label, tombol |
| `--font-size-caption` | 0.8125rem (13px) | Bantuan, badge, galat |

## Jarak, bentuk, dan efek

| Token | Nilai | Penggunaan |
|---|---|---|
| `--space-1` s.d. `--space-7` | 4, 8, 12, 16, 24, 32, 48 px | Margin, padding, gap |
| `--radius-control` | 10px | Tombol, field |
| `--radius-card` | 16px | Kartu, panel |
| `--radius-pill` | 999px | Badge, chip |
| `--shadow-card` / `--shadow-hover` | bayangan lembut / bayangan biru | Kartu diam / kartu saat hover |
| `--focus-ring` | 3px kuning | Fokus keyboard |
| `--transition` | 160ms (0 jika pengguna memilih kurangi gerakan) | Hover dan fokus |
| `--touch-min` | 44px | Tinggi minimum tombol dan field |
| `--container-max` | 1160px | Lebar konten |

## Aturan pemakaian

- File CSS lain hanya memakai `var(--...)`.
- Kuning dipakai sebagai penanda, bukan untuk teks panjang; teks di atas kuning selalu `--color-on-accent`.
- Status selalu berupa teks di dalam badge; warna dan titik bulat hanya penguat.
