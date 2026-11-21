# 03 - Role, Relasi Pemilik, dan Seeder Akun

## Migration baru (migration Pertemuan 5 tidak diubah)

| Berkas | Isi |
|---|---|
| `2026_11_21_233927_add_role_to_users_table.php` | `users.role` string(20), default `pengguna` |
| `2026_11_21_233928_add_user_id_to_reports_table.php` | `reports.user_id` foreignId nullable, `nullOnDelete` |
| `2026_11_21_233930_add_user_id_to_claims_table.php` | `claims.user_id` foreignId nullable, `nullOnDelete` |

Semua punya `down()` (`dropColumn` / `dropConstrainedForeignId`) sehingga bisa di-rollback.

**Mengapa nullable?** Data Pertemuan 5 belum punya pemilik. Data baru selalu diisi controller dari pengguna yang login.

**Mengapa nullOnDelete?** Jika akun dihapus, laporan dan klaimnya tetap tersimpan (tanpa pemilik), sehingga riwayat barang tidak hilang.

## Model

- `User`: `isAdmin()`, `reports()` (hasMany), `claims()` (hasMany), konstanta `ROLE_ADMIN` dan `ROLE_PENGGUNA`.
- `Report::user()` dan `Claim::user()`: belongsTo.
- **Keamanan:** `role` dan `user_id` **tidak** dimasukkan ke `$fillable`. Peran tidak bisa disisipkan dari form registrasi (diuji: daftar dengan `role=admin` tetap menjadi `pengguna`), dan `user_id` tidak bisa disisipkan dari form laporan (diuji). Seeder memakai `forceFill()`.

## Seeder akun (`database/seeders/UserSeeder.php`)

| Email | Nama | Peran |
|---|---|---|
| admin@lostfound.test | Pak Slamet | admin |
| dina@lostfound.test | Dina | pengguna |
| rani@lostfound.test | Rani | pengguna |
| budi@lostfound.test | Budi | pengguna |
| raka@lostfound.test | Raka | pengguna |

Kata sandi semua akun uji: `password` (hanya untuk latihan). Memakai `updateOrCreate()` agar aman dijalankan ulang. `DatabaseSeeder` memanggil `UserSeeder` lebih dulu, lalu `ReportSeeder` menghubungkan laporan dan klaim ke akun berdasarkan nama.

## Menjalankan dan memeriksa

```bash
php artisan migrate:fresh --seed
php artisan tinker --execute "echo json_encode(App\Models\User::pluck('role','email'));"
```

Hasil:

```
{"admin@lostfound.test":"admin","dina@lostfound.test":"pengguna","rani@lostfound.test":"pengguna","budi@lostfound.test":"pengguna","raka@lostfound.test":"pengguna"}
laporan tanpa pemilik: 0, klaim tanpa pemilik: 0
Dina punya 2 laporan dan 2 klaim
```
