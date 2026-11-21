# 01 - Model Data

Pemetaan modul ke proyek UNY Lost and Found:

| Modul (studi kasus ruang) | Proyek ini | Alasan |
|---|---|---|
| `rooms` (ruang) | `reports` (laporan barang hilang/temuan) | Entitas utama yang ditampilkan di kartu, pencarian, dan detail |
| `bookings` (pengajuan) | `claims` (klaim kepemilikan) | Diajukan pengguna terhadap satu entitas utama, punya status |
| Validasi kapasitas | Tanggal tidak boleh di masa depan; klaim hanya untuk barang temuan terverifikasi | Aturan isian yang relevan |
| Pencegahan bentrok jadwal | Satu barang hanya boleh punya satu klaim **Disetujui**; satu pengklaim tidak boleh punya dua klaim aktif untuk barang yang sama | Padanan "tidak boleh ada dua pemakai untuk sumber daya yang sama" |

## Pemetaan field antarmuka ke kolom database

| Antarmuka (P4) | Field | Kolom database | Tipe | Wajib |
|---|---|---|---|---|
| Form Lapor | Jenis laporan | `reports.jenis` | varchar(10): Hilang / Temuan | Ya |
| Form Lapor, Detail | Nama barang | `reports.nama` | varchar(100) | Ya |
| Form Lapor, Detail | Kategori | `reports.kategori` | varchar(30) | Ya |
| Form Lapor, Detail | Lokasi | `reports.lokasi` | varchar(100) | Ya |
| Form Lapor, Detail | Tanggal | `reports.tanggal` | date | Ya |
| Form Lapor, Detail | Ciri barang | `reports.ciri` | text | Ya |
| Form Lapor, Panel petugas | Ciri rahasia | `reports.ciri_rahasia` | text | Tidak |
| Form Lapor, Riwayat (klaim disetujui) | Dititipkan di | `reports.dititipkan_di` | varchar(100) | Ya untuk Temuan |
| Riwayat, Panel petugas | Status | `reports.status` | varchar(30), default "Menunggu verifikasi" | Sistem |
| Riwayat | Alasan penolakan | `reports.alasan` | varchar(500) | Saat ditolak |
| Kelola Laporan | Pelapor | `reports.pelapor` | varchar(100) | Sistem |
| Detail (form klaim) | Ciri pembuktian | `claims.bukti` | text | Ya (min. 10 karakter) |
| Detail (form klaim) | Catatan tambahan | `claims.catatan` | varchar(500) | Tidak |
| Riwayat, Panel petugas | Status klaim | `claims.status` | varchar(20), default "Menunggu" | Sistem |
| Riwayat | Alasan penolakan klaim | `claims.alasan` | varchar(500) | Saat ditolak |
| Panel petugas | Pengklaim | `claims.pengklaim` | varchar(100) | Sistem |
| Semua halaman | Kode LP-001 / KL-001 | dihitung dari `id` (accessor `kode`) | - | - |

Foto barang divalidasi (JPG/PNG, maks. 2 MB) tetapi belum disimpan; lihat `README.md`.

## Relasi

```
reports                         claims
- id (PK)            1 ──── N   - id (PK)
- jenis                         - report_id (FK -> reports.id, cascade delete)
- nama                          - pengklaim
- kategori                      - bukti
- lokasi                        - catatan
- tanggal                       - status
- ciri                          - alasan
- ciri_rahasia                  - timestamps
- dititipkan_di
- status
- alasan
- pelapor
- timestamps
```

- `Report::claims()` → `hasMany(Claim::class)`
- `Claim::report()` → `belongsTo(Report::class)`
- Menghapus laporan ikut menghapus klaimnya (`cascadeOnDelete`).

## Status

| Entitas | Status | Diubah oleh |
|---|---|---|
| Laporan | Menunggu verifikasi → Terverifikasi / Ditolak; Terverifikasi → Dikembalikan (setelah serah terima) | Petugas |
| Klaim | Menunggu → Disetujui / Ditolak | Petugas |

## Persiapan Pertemuan 6

Kolom `reports.pelapor` dan `claims.pengklaim` (teks) akan diganti `user_id` (foreign key ke `users`) saat autentikasi dibuat.
