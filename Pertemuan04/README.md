# Pertemuan 4 - Design System, Struktur Proyek, Komponen UI, dan Antarmuka Awal

**Proyek:** UNY Lost and Found
**Pengerjaan:** perorangan - Arifan Ismail (24051130061)
**Tanggal:** 20 November 2026

## Cara menjalankan

Kode aplikasi ada di folder `aplikasi/` (Laravel 13, PHP 8.3+).

```bash
cd aplikasi
composer install
php -r "copy('.env.example', '.env');"
php artisan key:generate
php -r "touch('database/database.sqlite');"
php artisan migrate
php artisan serve
```

Buka `http://127.0.0.1:8000`. Langkah `copy` sampai `migrate` hanya perlu sekali (jika `.env` belum ada). Halaman penting:

| URL | Isi |
|---|---|
| `/` | Beranda / Cari |
| `/cari?kategori=Dompet` | Hasil pencarian |
| `/barang/LP-001` | Detail barang + form klaim |
| `/barang/LP-006` | Barang yang sudah diklaim |
| `/lapor` | Form lapor barang |
| `/riwayat` | Riwayat |
| `/petugas` | Layar petugas |
| `/komponen` | Halaman uji komponen |

Uji otomatis: `php artisan test`.

## Struktur proyek

```
aplikasi/
  app/Http/Controllers/   Barang, Laporan, Riwayat, Petugas
  app/Support/DemoData.php  data contoh (simulasi)
  public/css/             tokens, base, layout, components
  resources/views/
    components/           layout, navbar, button, form-field, item-card, status-badge, alert, empty-state
    pages/                beranda, hasil, detail, lapor, riwayat, petugas, komponen
    errors/404.blade.php
  routes/web.php
  tests/Feature/AntarmukaPertemuan4Test.php
Pertemuan04/              dokumen pengumpulan (folder ini)
```

## Berkas pengumpulan

| Berkas | Status |
|---|---|
| 01-design-tokens.md | Selesai |
| 02-component-inventory.md | Selesai |
| 03-ui-screenshots.pdf | Selesai |
| 04-test-checklist.md | Selesai |
| 05-keputusan-implementasi.md | Selesai |

## Tangkapan layar yang perlu diambil untuk 03-ui-screenshots.pdf

Desktop dan mobile (di Chrome: F12, ikon perangkat, pilih lebar 390 px):

1. Beranda `/`
2. Hasil `/cari?kategori=Dompet` dan hasil kosong `/cari?q=zzz`
3. Detail `/barang/LP-001`, lalu klik Kirim Klaim tanpa isian (state galat)
4. Barang sudah diklaim `/barang/LP-006`
5. Form lapor setelah klik Kirim Laporan tanpa isian (state galat)
6. Riwayat dengan pesan sukses (kirim klaim yang benar dulu)
7. Petugas `/petugas?kode=KL-005` (peringatan klaim ganda)
8. Halaman uji `/komponen`

Tempel ke Word atau Google Docs, beri judul tiap gambar, lalu simpan sebagai PDF.

## Kontribusi

| Nama | Bagian pekerjaan | Tautan commit |
|---|---|---|
| Arifan Ismail | Seluruh bagian (perorangan) | (isi setelah push) |
