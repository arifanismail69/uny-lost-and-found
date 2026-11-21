<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Tabel laporan barang (hilang atau temuan). Padanan tabel "rooms" pada modul. */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 10);                     // Hilang | Temuan
            $table->string('nama', 100);                     // nama barang
            $table->string('kategori', 30);
            $table->string('lokasi', 100);
            $table->date('tanggal');                         // tanggal hilang / ditemukan
            $table->text('ciri');                            // ciri umum (tampil publik)
            $table->text('ciri_rahasia')->nullable();        // hanya untuk petugas
            $table->string('dititipkan_di', 100)->nullable(); // wajib untuk temuan
            $table->string('status', 30)->default('Menunggu verifikasi');
            $table->string('alasan', 500)->nullable();       // alasan penolakan
            $table->string('pelapor', 100);                  // diganti user_id pada Pertemuan 6
            $table->timestamps();

            $table->index(['jenis', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
