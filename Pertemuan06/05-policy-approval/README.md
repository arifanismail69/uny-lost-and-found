# 05 - Policy dan Alur Persetujuan

## Policy (ditemukan otomatis oleh Laravel karena mengikuti konvensi nama)

`app/Policies/ReportPolicy.php`

| Method | Aturan |
|---|---|
| `viewAny` | Hanya admin (Kelola Laporan) |
| `create` | Semua pengguna yang login |
| `update`, `delete` | Admin, atau pemilik selama status "Menunggu verifikasi" |
| `review` | Hanya admin (verifikasi/tolak) |

`app/Policies/ClaimPolicy.php` (padanan BookingPolicy)

| Method | Aturan |
|---|---|
| `create` | Hanya pengguna biasa (petugas tidak mengklaim barang) |
| `update`, `delete` | Admin, atau pemilik selama status "Menunggu" |
| `review` | Hanya admin (setujui/tolak, serah terima) |

## Pemakaian di controller

Laravel 11+ tidak lagi memuat trait `AuthorizesRequests` di controller dasar, jadi dipakai `Gate::authorize()`:

```php
public function update(Request $request, Claim $claim)
{
    Gate::authorize('update', $claim);   // 403 jika bukan pemilik/admin atau sudah diputuskan
    $claim->update($this->validasi($request));
    ...
}
```

- `ReportController` dan `ClaimController`: `create/store/edit/update/destroy` memanggil `Gate::authorize`.
- `PetugasController`: setiap aksi memanggil `Gate::authorize('review', ...)` sebagai lapisan kedua selain middleware `role:admin`.
- **user_id diambil dari pengguna yang login**, tidak pernah dari form: `$claim->user()->associate($request->user())`.
- Riwayat hanya menampilkan data milik sendiri: `$request->user()->claims()->with('report')`.

## Alur persetujuan

| Entitas | Alur | Aksi petugas |
|---|---|---|
| Laporan | Menunggu verifikasi → Terverifikasi / Ditolak (alasan wajib) | Verifikasi, Tolak |
| Klaim | Menunggu → Disetujui / Ditolak (alasan wajib) | Setujui, Tolak |
| Barang | Terverifikasi → Dikembalikan | Catat sudah diserahkan |

Aturan bisnis Pertemuan 5 tetap berlaku: satu barang hanya satu klaim disetujui (dicek dalam transaksi). **Klaim yang ditolak membebaskan barang**: pengguna bisa mengajukan klaim baru (padanan "jadwal bebas kembali" di modul, diuji di `test_klaim_ditolak_membebaskan_barang_untuk_klaim_baru`).

## Tampilan sesuai hak akses

- Riwayat: tombol Ubah/Hapus/Batalkan memakai `@can('update', $data)`.
- Detail barang: tamu melihat "Masuk untuk mengajukan klaim", petugas melihat keterangan bahwa petugas tidak mengklaim, pengguna melihat form klaim.
- Navigasi: tamu (Beranda, Cari, Masuk, Daftar); pengguna (Beranda, Cari, Lapor, Riwayat, Dashboard, Keluar); petugas (Dashboard, Laporan dan Klaim, Kelola Laporan, Cari, Keluar), header bertanda "Panel Petugas".
- Dashboard: nama, peran, dan ringkasan jumlah (milik sendiri untuk pengguna, seluruh data untuk petugas).

Menyembunyikan tombol hanya untuk kenyamanan; pengamanan sebenarnya ada di middleware dan policy.
