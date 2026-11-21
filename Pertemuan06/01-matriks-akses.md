# 01 - Matriks Hak Akses

Peran pada proyek UNY Lost and Found:

| Peran modul | Peran proyek | Nilai `users.role` | Contoh akun uji |
|---|---|---|---|
| Tamu | Tamu (belum login) | - | - |
| Peminjam | Pengguna (pelapor dan pengklaim) | `pengguna` (bawaan akun baru) | `dina@lostfound.test` |
| Admin | Petugas keamanan / pos informasi | `admin` | `admin@lostfound.test` |

## Matriks

| Fitur | Tamu | Pengguna | Admin (petugas) | Dijaga oleh |
|---|---|---|---|---|
| Beranda, pencarian, detail barang temuan | Boleh | Boleh | Boleh | (publik) |
| Daftar akun, masuk | Boleh | - | - | middleware `guest` (Breeze) |
| Dashboard, profil, keluar | Tidak (ke /login) | Boleh (data sendiri) | Boleh (ringkasan semua data) | middleware `auth` |
| Membuat laporan barang | Tidak (ke /login) | Boleh | Boleh | `auth` + `ReportPolicy::create` |
| Mengubah/menghapus laporan | Tidak (ke /login) | Milik sendiri dan masih "Menunggu verifikasi" | Semua | `auth` + `ReportPolicy::update/delete` |
| Riwayat (laporan dan klaim) | Tidak (ke /login) | Milik sendiri saja | - (memakai panel petugas) | `auth` + query `user()->reports()/claims()` |
| Mengajukan klaim | Tidak (ke /login) | Boleh | Tidak (403) | `auth` + `ClaimPolicy::create` |
| Mengubah/membatalkan klaim | Tidak (ke /login) | Milik sendiri dan masih "Menunggu" | Semua | `auth` + `ClaimPolicy::update/delete` |
| Kelola Laporan (daftar semua laporan) | Tidak (ke /login) | Tidak (403) | Boleh | `auth` + `role:admin` + `ReportPolicy::viewAny` |
| Panel Laporan dan Klaim | Tidak (ke /login) | Tidak (403) | Boleh | `auth` + `role:admin` |
| Verifikasi/tolak laporan | Tidak | Tidak (403) | Boleh | `role:admin` + `ReportPolicy::review` |
| Setujui/tolak klaim, catat serah terima | Tidak | Tidak (403) | Boleh | `role:admin` + `ClaimPolicy::review` |

## Perubahan struktur data

| Tabel | Kolom baru | Tipe | Keterangan |
|---|---|---|---|
| `users` | `role` | string(20), default `pengguna` | `admin` atau `pengguna` |
| `reports` | `user_id` | foreignId, nullable, nullOnDelete | Pemilik laporan |
| `claims` | `user_id` | foreignId, nullable, nullOnDelete | Pemilik klaim (padanan `bookings.user_id`) |

Relasi: `User (1) — (N) Report`, `User (1) — (N) Claim`, `Report (1) — (N) Claim`.

Kolom teks `reports.pelapor` dan `claims.pengklaim` dari Pertemuan 5 tetap ada sebagai catatan nama saat data dibuat; otorisasi memakai `user_id`.
