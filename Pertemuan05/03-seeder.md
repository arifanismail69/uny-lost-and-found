# 03 - Seeder

Berkas: `aplikasi/database/seeders/ReportSeeder.php`, dipanggil dari `DatabaseSeeder`.

Data contoh Pertemuan 4 (`App\Support\DemoData`) dipindahkan ke seeder lalu kelas DemoData dihapus, sehingga semua halaman membaca dari database.

## Isi data awal

**10 laporan**

| Kode | Jenis | Barang | Status | Tujuan uji |
|---|---|---|---|---|
| LP-001 | Temuan | Dompet kulit hitam | Terverifikasi | Klaim baru (ada klaim Rani menunggu) |
| LP-002 | Temuan | Dompet cokelat | Terverifikasi | Klaim baru tanpa klaim lain |
| LP-003 | Temuan | Botol minum biru | Terverifikasi | Pencarian kategori lain |
| LP-004 | Temuan | Kunci motor Honda | Terverifikasi | - |
| LP-005 | Temuan | Payung lipat hijau | Terverifikasi | Klaim Dina ditolak |
| LP-006 | Temuan | Dompet hitam polos | Terverifikasi | Sudah punya klaim disetujui (uji aturan bisnis) |
| LP-007 | Temuan | Tas ransel biru | Dikembalikan | Tidak tampil publik |
| LP-008 | Temuan | Laptop abu-abu | Menunggu verifikasi | Uji verifikasi petugas |
| LP-009 | Temuan | Kunci loker | Ditolak | Alasan tampil di Riwayat Dina |
| LP-010 | Hilang | Tas ransel biru | Terverifikasi | Laporan kehilangan milik Dina |

**5 klaim**: KL-001 (Dina, LP-005, Ditolak), KL-002 (Rani, LP-001, Menunggu), KL-003 (Dina, LP-007, Disetujui), KL-004 (Rani, LP-006, Disetujui), KL-005 (Budi, LP-006, Menunggu).

## Menjalankan dan memeriksa

```bash
php artisan db:seed
php artisan tinker --execute "echo App\Models\Report::count();"
```

Hasil pemeriksaan:

```
10 laporan, 5 klaim, LP-001 punya 1 klaim, KL-002 milik Dompet kulit hitam
```
