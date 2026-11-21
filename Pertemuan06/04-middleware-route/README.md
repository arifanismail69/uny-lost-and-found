# 04 - Middleware Role dan Proteksi Route

## Middleware

`app/Http/Middleware/EnsureUserHasRole.php` menolak (403) pengguna yang perannya tidak ada dalam daftar:

```php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    $user = $request->user();
    if (! $user || ! in_array($user->role, $roles, true)) {
        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
    return $next($request);
}
```

Alias didaftarkan di `bootstrap/app.php`: `'role' => EnsureUserHasRole::class`.

## Susunan `routes/web.php`

| Grup | Middleware | Route |
|---|---|---|
| 1. Khusus petugas | `auth`, `role:admin` | `GET /laporan`, `GET /petugas`, `PATCH /petugas/laporan/{report}`, `PATCH /petugas/klaim/{claim}`, `PATCH /petugas/klaim/{claim}/serah-terima` |
| 2. Publik | - | `GET /`, `GET /cari`, `GET /barang/{report}`, `GET /komponen` |
| 3. Wajib login | `auth` | `/dashboard`, `/laporan/create`, `POST /laporan`, `/laporan/{report}/edit`, `PUT/DELETE /laporan/{report}`, `POST /barang/{report}/klaim`, `/klaim/{claim}/edit`, `PUT/DELETE /klaim/{claim}`, `/riwayat`, `/profile` |
| Breeze | `guest` / `auth` | `routes/auth.php` (login, register, logout, password) |

Grup admin ditulis paling atas, sesuai catatan urutan route di modul.

## Hasil uji

| Uji | Hasil |
|---|---|
| Tamu membuka `/dashboard`, `/riwayat`, `/laporan/create`, `/laporan`, `/petugas`, `/profile` | Diarahkan ke `/login` |
| Tamu mengirim klaim (POST) | Diarahkan ke `/login`, tidak ada data tersimpan |
| Pengguna membuka `/petugas` dan `/laporan` | 403 "Tidak diizinkan" |
| Pengguna mengirim PATCH persetujuan klaim | 403, status klaim tidak berubah |
| Admin membuka semua area | 200 |

## Perbaikan bug yang ditemukan

Filter status "Semua" di panel petugas mengirim nilai kosong. Laravel mengubah nilai kosong menjadi null sehingga filter jatuh kembali ke "Menunggu". Diperbaiki dengan nilai eksplisit `status=semua` (ditemukan oleh test `test_admin_masuk_area_petugas_dan_melihat_semua_data`).
