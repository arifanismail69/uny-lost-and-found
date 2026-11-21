# 06 - Checklist Pengujian Matriks Akses

Test otomatis: `php artisan test` → **58 test lulus, 207 assertion** (termasuk 6 file test bawaan Breeze dan `AksesPertemuan6Test`). Test memakai SQLite in-memory, tidak menyentuh database pengembangan.

Akun uji: `admin@lostfound.test` dan `dina@lostfound.test` (kata sandi `password`).

| No | Skenario | Akun | Hasil yang diharapkan | Hasil |
|---|---|---|---|---|
| 1 | Buka beranda dan pencarian | Tamu | Barang tampil | Sudah (test + browser) |
| 2 | Buka `/riwayat`, `/dashboard`, `/laporan/create` | Tamu | Diarahkan ke `/login` | Sudah (test + browser) |
| 3 | Registrasi akun baru (termasuk mencoba `role=admin`) | Tamu | Akun tersimpan, role = pengguna | Sudah (test) |
| 4 | Login dengan password salah | Tamu | Pesan galat, tidak masuk | Sudah (test) |
| 5 | Buka `/petugas` dan `/laporan` | Pengguna | 403 | Sudah (test + browser) |
| 6 | Kirim klaim valid | Pengguna | Tersimpan, `user_id` terisi, status Menunggu | Sudah (test) |
| 7 | Lihat Riwayat (Rani vs Dina) | Pengguna | Hanya data milik sendiri | Sudah (test) |
| 8 | Buka edit klaim/laporan milik orang lain | Pengguna | 403 | Sudah (test) |
| 9 | Ubah klaim sendiri sebelum dan sesudah disetujui | Pengguna | Berhasil saat Menunggu; 403 setelah Disetujui | Sudah (test) |
| 10 | Lihat semua data, setujui klaim | Admin | Semua tampil; status menjadi Disetujui | Sudah (test + browser dashboard) |
| 11 | Tolak klaim, lalu ajukan klaim baru untuk barang sama | Admin / Pengguna | Barang bebas kembali, klaim baru diterima | Sudah (test) |
| 12 | Ubah/hapus laporan apa pun, verifikasi laporan | Admin | Berhasil, data berubah di DB | Sudah (test) |
| 13 | Logout lalu buka `/dashboard` | Siapa saja | Diarahkan ke `/login` | Sudah (test) |
| 14 | Halaman login, dashboard, profil, panel petugas di layar sempit | Siapa saja | Tidak meluber, tombol Keluar terlihat | Sudah (browser, lebar 390 px) |

## Uji tambahan

| Skenario | Hasil |
|---|---|
| `user_id` disisipkan dari form laporan | Diabaikan, pemilik tetap akun yang login |
| Petugas mencoba mengajukan klaim | 403 |
| Tamu mengirim POST klaim langsung | Ke `/login`, tidak ada klaim tersimpan |
| Login di browser sebagai Dina dari `/riwayat` | Setelah login kembali ke `/riwayat` |
| Dashboard petugas | 1 laporan menunggu, 2 klaim menunggu, 1 barang siap diserahkan, 10 total (sesuai data seeder) |

## Belum dilakukan

- Tangkapan layar untuk `07-screenshots.pdf`.
- Pengujian dengan MySQL dan dengan dua browser bersamaan oleh orang lain.
