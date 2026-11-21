# Pertemuan 5 - Basis Data, Model Data, Migration, Seeder, dan CRUD

**Proyek:** UNY Lost and Found
**Pengerjaan:** perorangan - Arifan Ismail (24051130061)
**Tanggal:** 21 November 2026

Antarmuka Pertemuan 4 kini terhubung ke database: data contoh diganti tabel `reports` dan `claims`, form lapor dan klaim benar-benar menyimpan data, dan petugas dapat mengubah status.

## Cara menjalankan

Paling mudah: klik dua kali `Jalankan Aplikasi.bat` di folder proyek. Atau lewat terminal:

```bash
cd aplikasi
php artisan migrate:fresh --seed
php artisan serve
```

`migrate:fresh --seed` membuat ulang semua tabel dan mengisi data awal (data lama terhapus). Uji otomatis: `php artisan test`.

## Database

Saat ini memakai **SQLite** (`aplikasi/database/database.sqlite`) karena MySQL belum terpasang di laptop pengembang. Kode tidak bergantung pada jenis database. Untuk pindah ke MySQL sesuai modul:

1. Pasang XAMPP atau Laragon, nyalakan MySQL, buat database `uny_lost_found`.
2. Aktifkan ekstensi `pdo_mysql` di `php.ini` (hapus tanda `;` di depan `extension=pdo_mysql`).
3. Ubah `aplikasi/.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=uny_lost_found
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan `php artisan config:clear` lalu `php artisan migrate --seed`.

## Halaman baru dan berubah

| URL | Isi |
|---|---|
| `/laporan` | Kelola Laporan (petugas): daftar, ubah, hapus |
| `/laporan/create` | Form lapor (menyimpan ke database) |
| `/laporan/{id}/edit` | Ubah laporan |
| `/barang/{id}` | Detail barang berdasarkan ID database |
| `/klaim/{id}/edit` | Ubah klaim |
| `/riwayat` | Laporan dan klaim milik pengguna + tombol Ubah/Hapus/Batalkan |
| `/petugas` | Verifikasi laporan, putuskan klaim, catat serah terima |

## Berkas pengumpulan

| Berkas | Status |
|---|---|
| 01-data-model.md | Selesai |
| 02-migration-model/ | Selesai |
| 03-seeder.md | Selesai |
| 04-crud-laporan/ (padanan crud-room) | Selesai |
| 05-crud-klaim/ (padanan crud-booking) | Selesai |
| 06-test-checklist.md | Selesai |
| 07-screenshots.pdf | **Belum** - ambil sendiri (lihat di bawah) |
| Refleksi individu (bagian H modul) | **Belum** - tulis sendiri |

## Tangkapan layar untuk 07-screenshots.pdf

1. Kelola Laporan `/laporan`
2. Form lapor dengan galat (kirim kosong) dan pesan sukses di Riwayat setelah kirim yang benar
3. Riwayat berisi laporan baru (tombol Ubah/Hapus)
4. Form ubah laporan dan pesan "Laporan ... diperbarui."
5. Konfirmasi hapus dan pesan "Laporan ... dihapus."
6. Detail barang → kirim klaim → Riwayat menampilkan klaim
7. Klaim ganda ditolak (kirim klaim kedua untuk barang yang sama)
8. Petugas `/petugas?kode=KL-005`: peringatan, tombol Setujui nonaktif
9. Petugas menyetujui KL-002 lalu "Catat sudah diserahkan"
10. Isi database sebelum dan sesudah: buka `aplikasi/database/database.sqlite` dengan **DB Browser for SQLite**, atau tangkapan layar terminal `php artisan tinker` (contoh perintah di `06-test-checklist.md`)
11. Satu halaman di lebar mobile 390 px

## Catatan keputusan

- Pengguna masih disimulasikan ("Dina"); kolom `pelapor`/`pengklaim` akan diganti `user_id` pada Pertemuan 6.
- Akses petugas ke ubah/hapus laporan sementara ditandai parameter `?petugas=1`. Ini **bukan pengamanan**; pembatasan sebenarnya dibuat dengan login dan middleware di Pertemuan 6.
- Foto barang divalidasi tetapi belum disimpan (butuh `storage:link` dan pengaturan berkas); dijadwalkan bersama pengamanan unggahan.
- Laravel Boost (diminta `aplikasi/CLAUDE.md`) belum terpasang karena perintah pemasangannya ditolak pengaturan izin.

## Kontribusi

| Nama | Bagian pekerjaan | Tautan commit |
|---|---|---|
| Arifan Ismail | Seluruh bagian (perorangan) | (isi setelah push) |
