<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
| Tiga lapis pengamanan:
| 1. middleware 'auth'        : sudah login? (tamu diarahkan ke /login)
| 2. middleware 'role:admin'  : perannya admin? (selain admin mendapat 403)
| 3. Policy (Gate::authorize) : boleh mengubah data INI? (di controller)
*/

// 1) Khusus admin (petugas)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
    Route::patch('/petugas/laporan/{report}', [PetugasController::class, 'putuskanLaporan'])->name('petugas.laporan');
    Route::patch('/petugas/klaim/{claim}', [PetugasController::class, 'putuskanKlaim'])->name('petugas.klaim');
    Route::patch('/petugas/klaim/{claim}/serah-terima', [PetugasController::class, 'serahTerima'])->name('petugas.serah-terima');
});

// 2) Publik: tamu boleh mencari dan melihat barang temuan
Route::get('/', [BarangController::class, 'beranda'])->name('beranda');
Route::get('/cari', [BarangController::class, 'cari'])->name('barang.cari');
Route::get('/barang/{report}', [BarangController::class, 'show'])->name('barang.show');
Route::view('/komponen', 'pages.komponen')->name('komponen');
Route::get('/awal', fn () => view('awal'));

// 3) Wajib login (pengguna dan admin); izin per data dicek policy
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('laporan', ReportController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->parameters(['laporan' => 'report']);
    Route::redirect('/lapor', '/laporan/create'); // alamat lama Pertemuan 4

    Route::post('/barang/{report}/klaim', [ClaimController::class, 'store'])->name('klaim.store');
    Route::resource('klaim', ClaimController::class)->only(['edit', 'update', 'destroy'])
        ->parameters(['klaim' => 'claim']);

    Route::get('/riwayat', RiwayatController::class)->name('riwayat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Login, daftar, lupa password, logout (Laravel Breeze)
require __DIR__.'/auth.php';
