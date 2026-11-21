<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Tabel klaim kepemilikan. Padanan tabel "bookings" pada modul. 1 report : N claims. */
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->string('pengklaim', 100);                // diganti user_id pada Pertemuan 6
            $table->text('bukti');                           // ciri pembuktian
            $table->string('catatan', 500)->nullable();
            $table->string('status', 20)->default('Menunggu'); // Menunggu | Disetujui | Ditolak
            $table->string('alasan', 500)->nullable();
            $table->timestamps();

            $table->index(['report_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
