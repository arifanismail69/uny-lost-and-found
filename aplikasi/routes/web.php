<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

// Pengguna (H-02 s.d. H-06). Autentikasi menyusul di Pertemuan 6.
Route::get('/', [BarangController::class, 'beranda'])->name('beranda');
Route::get('/cari', [BarangController::class, 'cari'])->name('barang.cari');
Route::get('/barang/{kode}', [BarangController::class, 'show'])->name('barang.show');
Route::post('/barang/{kode}/klaim', [BarangController::class, 'klaim'])->name('barang.klaim');
Route::get('/lapor', [LaporanController::class, 'create'])->name('laporan.create');
Route::post('/lapor', [LaporanController::class, 'store'])->name('laporan.store');
Route::get('/riwayat', RiwayatController::class)->name('riwayat');

// Petugas (H-07)
Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
Route::post('/petugas/{kode}/keputusan', [PetugasController::class, 'keputusan'])->name('petugas.keputusan');

// Halaman uji komponen (design system)
Route::view('/komponen', 'pages.komponen')->name('komponen');

// Halaman awal Pertemuan 1 tetap dipertahankan
Route::get('/awal', fn () => view('awal'));
