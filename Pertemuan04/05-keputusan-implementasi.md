# 05 - Keputusan Implementasi

| No | Keputusan | Alasan |
|---|---|---|
| 1 | Laravel 13 + Blade, satu proyek di folder `aplikasi/` dalam repositori yang sama dengan dokumen | Pertemuan 5 dan 6 melanjutkan proyek ini (CRUD, autentikasi) |
| 2 | CSS biasa di `public/css`, tanpa Vite | Node.js belum terpasang di laptop; CSS biasa langsung jalan. Bisa dipindah ke Vite nanti |
| 3 | Design token sebagai CSS variables di `tokens.css` | Satu sumber nilai visual |
| 4 | Komponen Blade anonymous (`<x-button>`, `<x-form-field>`, dst.) | Mengurangi pengulangan markup; parameter jelas |
| 5 | Validasi di server dengan `$request->validate()` dan pesan Bahasa Indonesia | Persis seperti nanti saat data disimpan; galat tampil lewat `@error` dan `old()` |
| 6 | Pengiriman form masih simulasi (redirect + pesan sukses, tidak disimpan) | Modul membolehkan simulasi; penyimpanan di Pertemuan 5 |
| 7 | Data contoh di `App\Support\DemoData` dengan nama field sesuai rencana tabel | Mudah dipindah ke migration dan seeder |
| 8 | Pengguna (Dina) dan petugas (Pak Slamet) disimulasikan, beralih lewat tautan di header | Autentikasi dan peran di Pertemuan 6 |
| 9 | Barang Menunggu/Ditolak/Dikembalikan tidak bisa dibuka publik (404) | AC-04.3, AC-04.5 |
| 10 | Aturan "satu klaim disetujui per barang" diperiksa di tampilan dan di server | AC-09.2; mencegah dilewati lewat permintaan langsung |
| 11 | Locale `id` dan zona waktu `Asia/Jakarta` | Tanggal berbahasa Indonesia; batas "hari ini" sesuai WIB |
| 12 | Test fitur `tests/Feature/AntarmukaPertemuan4Test.php` | Bukti uji yang bisa diulang |

## Route dan pemetaan ke Pertemuan 3

| Route | Halaman (ID P3) | User story |
|---|---|---|
| `GET /` | Beranda / Cari (H-02) | US-04, US-02, US-03 |
| `GET /cari` | Hasil Pencarian (H-03) | US-04 |
| `GET /barang/{kode}`, `POST /barang/{kode}/klaim` | Detail Barang + klaim (H-04) | US-04, US-05 |
| `GET /lapor`, `POST /lapor` | Form Lapor (H-05) | US-02, US-03 |
| `GET /riwayat` | Riwayat (H-06) | US-06 |
| `GET /petugas`, `POST /petugas/{kode}/keputusan` | Laporan dan Klaim (H-07) | US-07, US-08, US-09 |
| `GET /komponen` | Halaman uji komponen | - |
| (404) | Tidak ditemukan (H-08) | - |

## Calon struktur data untuk Pertemuan 5

- `reports`: id, kode, jenis (Hilang/Temuan), nama, kategori, lokasi, tanggal, ciri, ciri_rahasia, dititipkan_di, foto, status, alasan, user_id
- `claims`: id, kode, report_id, user_id, bukti, catatan, status (Menunggu/Disetujui/Ditolak), alasan
- Relasi: satu report memiliki banyak claims (one-to-many)
