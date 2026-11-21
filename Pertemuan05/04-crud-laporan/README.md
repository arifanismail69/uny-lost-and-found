# 04 - CRUD Laporan (padanan CRUD Ruang)

Controller: `aplikasi/app/Http/Controllers/ReportController.php` (resource controller)
Route: `Route::resource('laporan', ReportController::class)->except('show')`
View: `aplikasi/resources/views/laporan/index.blade.php`, `laporan/form.blade.php` (dipakai untuk create dan edit)
Detail berdasarkan ID: `GET /barang/{report}` di `BarangController@show`

| Operasi | Route | Siapa | Keterangan |
|---|---|---|---|
| Read (daftar) | `GET /laporan` | Petugas (menu Kelola Laporan) | Filter jenis dan status; jumlah klaim tiap laporan (`withCount`) |
| Read (publik) | `GET /`, `GET /cari`, `GET /barang/{id}` | Semua | Hanya barang temuan terverifikasi (`Report::publik()`) |
| Create | `GET /laporan/create`, `POST /laporan` | Pengguna | Tersimpan dengan status "Menunggu verifikasi" |
| Update | `GET /laporan/{id}/edit`, `PUT /laporan/{id}` | Pelapor (selama menunggu) dan petugas | Pelapor lain atau laporan yang sudah diputuskan: 403 |
| Delete | `DELETE /laporan/{id}` | Pelapor (selama menunggu) dan petugas | Konfirmasi sebelum hapus; klaim ikut terhapus |
| Ubah status | `PATCH /petugas/laporan/{id}` | Petugas | Verifikasi atau tolak (alasan wajib) |

## Validasi

`nama`, `kategori`, `ciri`, `lokasi`, `tanggal` wajib; `tanggal` tidak boleh setelah hari ini; `kategori` dan `lokasi` harus dari daftar; `dititipkan_di` wajib untuk Temuan; foto JPG/PNG maks. 2 MB. Pesan galat dalam Bahasa Indonesia tampil di bawah kolom.

## Uji

| Uji | Bukti | Hasil |
|---|---|---|
| Create dari browser | Riwayat menampilkan LP-011; `reports` 10 → 11 baris, `status = Menunggu verifikasi`, `pelapor = Dina` | Sesuai |
| Read | `/laporan` menampilkan semua baris tabel; filter status Ditolak hanya menampilkan LP-009 | Sesuai (test) |
| Update | Nama berubah di database setelah simpan | Sesuai (test) |
| Delete | Baris laporan dan klaimnya hilang | Sesuai (test) |
| Verifikasi petugas dari browser | LP-011 menjadi Terverifikasi dan langsung muncul di `/cari` | Sesuai |
