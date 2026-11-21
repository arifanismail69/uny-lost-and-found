# Pertemuan 6 - Autentikasi, Otorisasi, Role, Middleware, dan Policy

**Proyek:** UNY Lost and Found
**Pengerjaan:** perorangan - Arifan Ismail (24051130061)
**Tanggal:** 21 November 2026

Aplikasi kini memakai akun: tamu hanya bisa mencari barang, pengguna bisa melapor dan mengklaim atas nama akunnya sendiri, dan petugas (admin) memverifikasi laporan serta memutuskan klaim. Pengguna simulasi "Dina" dan tanda `?petugas=1` dari Pertemuan 5 dihapus.

## Cara menjalankan

```bash
cd aplikasi
composer install
php artisan migrate:fresh --seed
php artisan serve
```

Atau klik dua kali `Jalankan Aplikasi.bat`. Node.js/npm **tidak** diperlukan (lihat `02-auth-breeze`).

Di komputer lain yang sudah punya database: cukup `php artisan migrate` lalu `php artisan db:seed --class=UserSeeder`.

## Akun uji (hanya latihan)

| Email | Peran | Kata sandi |
|---|---|---|
| admin@lostfound.test | Petugas (admin) | password |
| dina@lostfound.test | Pengguna | password |
| rani@lostfound.test, budi@lostfound.test, raka@lostfound.test | Pengguna | password |

## Berkas pengumpulan

| Berkas | Status |
|---|---|
| 01-matriks-akses.md | Selesai |
| 02-auth-breeze/ | Selesai |
| 03-role-migration-seeder/ | Selesai |
| 04-middleware-route/ | Selesai |
| 05-policy-approval/ | Selesai |
| 06-test-checklist.md | Selesai |
| 07-screenshots.pdf | **Belum** - ambil sendiri (lihat di bawah) |
| Refleksi individu (bagian H modul) | **Belum** - tulis sendiri |

## Tangkapan layar untuk 07-screenshots.pdf

Pakai dua browser (atau satu biasa dan satu incognito) agar akun petugas dan pengguna bisa dibuka bersamaan.

1. Halaman Daftar dan Masuk
2. Tamu membuka `/riwayat` → diarahkan ke halaman Masuk
3. Dashboard pengguna (Dina) dan dashboard petugas
4. Pengguna membuka `/petugas` → halaman "Tidak diizinkan" (403)
5. Detail barang: tampilan tamu ("Masuk untuk mengajukan klaim") vs pengguna (form klaim)
6. Pengguna mengirim klaim → Riwayat menampilkan klaim dengan tombol Ubah/Batalkan
7. Petugas menyetujui klaim tersebut → di akun pengguna tombol Ubah hilang dan tempat pengambilan muncul
8. Pengguna membuka `/klaim/{id}/edit` milik orang lain → 403
9. Tabel `users` (kolom `role`) dan `claims` (kolom `user_id`) di DB Browser for SQLite
10. Halaman Masuk atau Dashboard di lebar mobile 390 px

## Catatan

- Database masih SQLite (lihat `Pertemuan05/README.md` untuk pindah ke MySQL).
- `composer audit` melaporkan 8 peringatan keamanan pada `laravel/framework` 13.36 dan beberapa paket Symfony (sudah ada sejak instalasi awal). Perbaikannya: `composer update` paket terkait; belum dilakukan di pertemuan ini.

## Kontribusi

| Nama | Bagian pekerjaan | Tautan commit |
|---|---|---|
| Arifan Ismail | Seluruh bagian (perorangan) | (isi setelah push) |
