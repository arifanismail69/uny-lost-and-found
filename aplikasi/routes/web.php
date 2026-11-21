<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

// Halaman publik (H-02 s.d. H-04)
Route::get('/', [BarangController::class, 'beranda'])->name('beranda');
Route::get('/cari', [BarangController::class, 'cari'])->name('barang.cari');
Route::get('/barang/{report}', [BarangController::class, 'show'])->name('barang.show');

// CRUD laporan (H-05 dan kelola laporan petugas). URL: /laporan, /laporan/create, /laporan/{id}/edit
Route::resource('laporan', ReportController::class)->except('show')->parameters(['laporan' => 'report']);
Route::redirect('/lapor', '/laporan/create'); // alamat lama Pertemuan 4

// CRUD klaim: buat dari detail barang; ubah/batalkan dari Riwayat
Route::post('/barang/{report}/klaim', [ClaimController::class, 'store'])->name('klaim.store');
Route::resource('klaim', ClaimController::class)->only(['edit', 'update', 'destroy'])->parameters(['klaim' => 'claim']);

// Riwayat pengguna (H-06)
Route::get('/riwayat', RiwayatController::class)->name('riwayat');

// Petugas (H-07). Pembatasan akses dengan login dibuat pada Pertemuan 6.
Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
Route::patch('/petugas/laporan/{report}', [PetugasController::class, 'putuskanLaporan'])->name('petugas.laporan');
Route::patch('/petugas/klaim/{claim}', [PetugasController::class, 'putuskanKlaim'])->name('petugas.klaim');
Route::patch('/petugas/klaim/{claim}/serah-terima', [PetugasController::class, 'serahTerima'])->name('petugas.serah-terima');

// Halaman uji komponen dan halaman awal Pertemuan 1
Route::view('/komponen', 'pages.komponen')->name('komponen');
Route::get('/awal', fn () => view('awal'));
