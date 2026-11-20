# 04 - Checklist Uji Antarmuka

Lingkungan: Laravel 13, PHP 8.5, `php artisan serve`. Diuji pada lebar desktop (1280 px) dan mobile (390 px) di browser, ditambah test otomatis `php artisan test` (10 test, 42 assertion, semua lulus).

| No | Pemeriksaan | Hasil | Bukti |
|---|---|---|---|
| 1 | Halaman dapat dijalankan dari instruksi README | Sudah | Semua route mengembalikan 200; route tidak valid 404 |
| 2 | Tidak ada error pada console browser | Sudah | Console bersih; semua CSS termuat (200). Satu-satunya 404 adalah uji sengaja `/barang/LP-999` |
| 3 | Warna, tipografi, jarak, radius mengikuti token | Sudah | CSS komponen dan layout hanya memakai `var(--...)` |
| 4 | Navigasi dari hasil ke detail dan form berjalan | Sudah | Hasil > Lihat Detail > Kirim Klaim > Riwayat; Beranda > Lapor > Riwayat |
| 5 | State kosong, error, dan sukses dapat ditampilkan | Sudah | Pencarian tanpa hasil; galat klaim/lapor/tolak; pesan sukses setelah kirim |
| 6 | Desktop dan mobile tidak terpotong atau meluber | Sudah | Lebar 390 px: tidak ada scroll horizontal di 10 halaman/state yang diperiksa |
| 7 | Tombol dan field memiliki label yang jelas | Sudah | Semua field memakai `<label for>`; tombol berlabel kata kerja |
| 8 | Kontras teks dan latar dapat dibaca | Sudah | Diukur di browser dengan rumus WCAG: semua pasangan utama 7.07-12.10 : 1 (lihat `01-design-tokens.md`) |
| 9 | Kode komponen tidak diduplikasi tanpa alasan | Sudah | 8 komponen Blade, lihat `02-component-inventory.md` |
| 10 | Perubahan sudah di-commit dan didorong ke repositori | Belum | Dilakukan pemilik proyek pada branch `feature/ui-pertemuan-4` |

## Skenario uji

| Skenario | Diharapkan | Hasil |
|---|---|---|
| Cari kategori Dompet | Hanya dompet tampil, yang sudah diklaim paling akhir | Sesuai |
| Cari kata yang tidak ada | Empty state + tombol Hapus filter | Sesuai |
| Buka barang Menunggu verifikasi / Dikembalikan | Halaman 404 dengan tautan kembali | Sesuai |
| Detail barang | Ciri rahasia tidak tampil | Sesuai |
| Barang sudah diklaim | Tombol klaim nonaktif | Sesuai |
| Kirim klaim kosong | "Ciri pembuktian wajib diisi." dan field merah | Sesuai |
| Kirim klaim valid | Ke Riwayat dengan pesan sukses | Sesuai |
| Lapor temuan kosong + tanggal masa depan | 5-6 galat; isian benar tidak hilang | Sesuai |
| Petugas Tolak tanpa alasan | "Alasan wajib diisi saat menolak." | Sesuai |
| Klaim kedua untuk barang yang sudah diklaim | Peringatan + Setujui nonaktif; ditolak juga di server | Sesuai |

## Belum diuji

- Uji dengan pengguna nyata.
- Pembaca layar (screen reader) dan alat pemeriksa kontras.
- Browser selain Chromium.
