# 02 - Autentikasi dengan Laravel Breeze

## Langkah yang dilakukan

1. Branch `feature/auth-role`, lalu commit pengaman `chore: add laravel/breeze package (snapshot before breeze:install)`.
2. `composer require laravel/breeze --dev` (versi 2.4).
3. `php artisan breeze:install blade`.
4. `routes/web.php` tertimpa Breeze (sesuai peringatan modul); route Pertemuan 4-5 dikembalikan dan disusun ulang (lihat `04-middleware-route`), dengan `require __DIR__.'/auth.php'` di akhir.

## Penyesuaian: tanpa Node.js

Breeze stack Blade memakai Tailwind dan Vite yang membutuhkan Node.js/npm. Node.js tidak terpasang di laptop pengembang (`breeze:install` menampilkan `'npm' is not recognized`). Karena itu:

- **Dipakai dari Breeze:** semua controller autentikasi (`app/Http/Controllers/Auth/*`), `LoginRequest`, `ProfileController`, `ProfileUpdateRequest`, `routes/auth.php`, dan test bawaan (`tests/Feature/Auth/*`, `ProfileTest`).
- **Ditulis ulang dengan design system UNY (tanpa Vite):** `auth/login`, `auth/register`, `auth/forgot-password`, `auth/reset-password`, `auth/confirm-password`, `auth/verify-email`, `profile/edit`, `dashboard`.
- **Dihapus:** layout dan komponen Tailwind Breeze yang tidak dipakai (`layouts/*`, `app/View/Components/*`, `text-input`, `primary-button`, dll.), `tailwind.config.js`, `postcss.config.js`; `package.json`, `vite.config.js`, `resources/css/app.css`, `resources/js/app.js` dikembalikan ke versi sebelum Breeze.

Nama field (`email`, `password`, `remember`, `password_confirmation`, `current_password`) dan route tetap sama dengan Breeze, sehingga test bawaan Breeze tetap lulus.

## Hasil

```
GET|HEAD  login     login › Auth\AuthenticatedSessionController@create
POST      login           Auth\AuthenticatedSessionController@store
GET|HEAD  register  register › Auth\RegisteredUserController@create
POST      register        Auth\RegisteredUserController@store
POST      logout    logout › Auth\AuthenticatedSessionController@destroy
```

- Registrasi menyimpan akun dengan password ter-hash dan peran `pengguna`, lalu diarahkan ke `/dashboard`.
- Login salah menampilkan pesan galat; login benar mengarah ke halaman yang tadi diminta atau `/dashboard`.
- Halaman login menampilkan daftar akun uji (khusus latihan).
- Pengiriman email lupa kata sandi belum dikonfigurasi (`MAIL_MAILER=log`): tautan tercatat di `storage/logs/laravel.log`.
