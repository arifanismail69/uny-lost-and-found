# 05 - CRUD Klaim (padanan CRUD Pengajuan)

Controller: `aplikasi/app/Http/Controllers/ClaimController.php`, keputusan petugas di `PetugasController.php`
View: form klaim di `pages/detail.blade.php`, `klaim/edit.blade.php`, daftar di `pages/riwayat.blade.php` dan `pages/petugas.blade.php`

| Operasi | Route | Siapa | Keterangan |
|---|---|---|---|
| Create | `POST /barang/{report}/klaim` | Pengguna | `report_id` dari barang yang dibuka; status awal "Menunggu" |
| Read | `GET /riwayat`, `GET /petugas` | Pengguna, petugas | Nama barang dibaca lewat relasi: `Claim::with('report')`, `$claim->report->nama` |
| Update | `GET /klaim/{id}/edit`, `PUT /klaim/{id}` | Pengklaim (selama menunggu) | Ubah ciri pembuktian |
| Delete | `DELETE /klaim/{id}` | Pengklaim (selama menunggu) | Tombol "Batalkan" dengan konfirmasi |
| Ubah status | `PATCH /petugas/klaim/{id}` | Petugas | Setujui atau tolak (alasan wajib) |
| Serah terima | `PATCH /petugas/klaim/{id}/serah-terima` | Petugas | Laporan menjadi "Dikembalikan" dan hilang dari pencarian |

## Aturan bisnis (padanan pencegahan bentrok jadwal)

```php
// ClaimController@store
if ($report->sudahDiklaim()) { /* tolak: barang sudah diklaim */ }

$ganda = $report->claims()->where('pengklaim', $nama)->where('status', 'Menunggu')->exists();
if ($ganda) { /* tolak: klaim ganda */ }

// PetugasController@putuskanKlaim: dicek ulang di dalam transaksi + kunci baris
DB::transaction(function () use ($claim) {
    $report = Report::whereKey($claim->report_id)->lockForUpdate()->first();
    if ($report->claims()->where('status', 'Disetujui')->exists()) return false;
    $claim->update(['status' => 'Disetujui']);
    return true;
});
```

## Skenario uji aturan bisnis

| Kasus | Barang | Kondisi | Aksi | Hasil |
|---|---|---|---|---|
| A | Dompet cokelat | Belum ada klaim | Dina mengklaim | Diterima |
| B | Dompet cokelat | Dina sudah punya klaim menunggu | Dina mengklaim lagi | Ditolak (klaim ganda) |
| C | Dompet hitam polos | Sudah ada klaim disetujui | Dina mengklaim | Ditolak (barang sudah diklaim) |
| D | Dompet hitam polos | Sudah ada klaim disetujui | Petugas menyetujui klaim Budi | Ditolak; status tetap Menunggu |
| E | Dompet kulit hitam | Klaim Rani menunggu | Petugas menyetujui, lalu catat serah terima | Disetujui, lalu barang Dikembalikan |

Semua kasus diuji otomatis di `aplikasi/tests/Feature/CrudPertemuan5Test.php`.
