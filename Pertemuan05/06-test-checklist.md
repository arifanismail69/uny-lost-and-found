# 06 - Checklist Pengujian End-to-End

Lingkungan: Laravel 13, PHP 8.5, SQLite (lihat README untuk MySQL). Test otomatis: `php artisan test` → **21 test lulus, 89 assertion** (memakai database terpisah di memori).

| No | Skenario | Bukti yang dicek | Hasil |
|---|---|---|---|
| 1 | Daftar laporan tampil | `/laporan` menampilkan seluruh baris tabel `reports` | Sudah |
| 2 | Tambah laporan | Dari browser: LP-011 tersimpan, `reports` 10 → 11 baris | Sudah |
| 3 | Edit laporan | Nilai `nama` berubah di database | Sudah (test) |
| 4 | Hapus laporan | Baris laporan dan klaimnya hilang | Sudah (test) |
| 5 | Kirim klaim valid | Baris `claims` baru berstatus Menunggu, tampil di Riwayat dengan nama barang | Sudah (test) |
| 6 | Isian tidak valid (padanan peserta > kapasitas) | Bukti kosong, tanggal masa depan, kolom wajib kosong ditolak | Sudah (test + browser P4) |
| 7 | Aturan bisnis (padanan jadwal bentrok) | Klaim ganda, klaim pada barang yang sudah diklaim, dan persetujuan klaim kedua ditolak | Sudah (test) |
| 8 | Edit klaim | Bukti berubah di database | Sudah (test) |
| 9 | Hapus (batalkan) klaim | Baris klaim hilang | Sudah (test) |
| 10 | Responsif mobile | Lebar 390 px: 7 halaman (termasuk Kelola Laporan dan form edit) tidak meluber | Sudah |

## Uji tambahan

| Skenario | Hasil |
|---|---|
| Verifikasi laporan dari browser → barang muncul di pencarian | Sesuai |
| Pengguna mengubah klaim orang lain atau klaim yang sudah diputuskan | Ditolak (403, halaman "Tidak diizinkan") |
| Pelapor mengubah laporan yang sudah diverifikasi | Ditolak (403) |
| Serah terima → barang berstatus Dikembalikan dan hilang dari pencarian | Sesuai (test) |
| Alamat lama `/lapor` | Dialihkan ke `/laporan/create` |

## Bukti tabel sebelum dan sesudah

```
SEBELUM: reports=10, claims=5
(kirim form Lapor Barang dari browser)
SESUDAH: reports=11
{"id":11,"jenis":"Temuan","nama":"Jam tangan hitam","lokasi":"Kantin FT","status":"Menunggu verifikasi","pelapor":"Dina","dititipkan_di":"Pos Satpam Gedung FT"}
```

## Belum dilakukan

- Pengujian dengan MySQL (MySQL belum terpasang di laptop pengembang).
- Tangkapan layar untuk `07-screenshots.pdf`.
