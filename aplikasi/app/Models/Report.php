<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    public const JENIS = ['Hilang', 'Temuan'];

    public const KATEGORI = ['Dompet', 'Tas', 'Botol', 'Kunci', 'Payung', 'Elektronik', 'Lainnya'];

    public const LOKASI = [
        'Perpustakaan Pusat', 'Perpustakaan lt.1', 'Perpustakaan lt.2', 'Kantin FT',
        'Parkir Barat', 'Masjid Kampus', 'Gedung Kuliah Bersama', 'Ruang Dosen',
    ];

    public const STATUS = ['Menunggu verifikasi', 'Terverifikasi', 'Ditolak', 'Dikembalikan'];

    protected $fillable = [
        'jenis', 'nama', 'kategori', 'lokasi', 'tanggal', 'ciri', 'ciri_rahasia',
        'dititipkan_di', 'status', 'alasan', 'pelapor',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    /** Relasi one-to-many: satu laporan memiliki banyak klaim. */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /** Kode tampilan, contoh LP-001 (dihitung dari id, tidak disimpan). */
    protected function kode(): Attribute
    {
        return Attribute::get(fn () => 'LP-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT));
    }

    /** Barang yang boleh tampil di pencarian publik (AC-04.3, AC-04.5). */
    public function scopePublik(Builder $query): Builder
    {
        return $query->where('jenis', 'Temuan')->where('status', 'Terverifikasi');
    }

    public function isPublik(): bool
    {
        return $this->jenis === 'Temuan' && $this->status === 'Terverifikasi';
    }

    /** Aturan bisnis: satu barang hanya boleh punya satu klaim yang disetujui (AC-09.2). */
    public function sudahDiklaim(): bool
    {
        if ($this->relationLoaded('claims')) {
            return $this->claims->contains('status', 'Disetujui');
        }

        return $this->claims()->where('status', 'Disetujui')->exists();
    }

    /** Pelapor hanya boleh mengubah/menghapus laporan selama belum diverifikasi (US-11). */
    public function bisaDiubahPelapor(): bool
    {
        return $this->status === 'Menunggu verifikasi';
    }
}
