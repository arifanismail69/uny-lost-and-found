<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    public const STATUS = ['Menunggu', 'Disetujui', 'Ditolak'];

    // user_id sengaja tidak fillable: diisi controller dari pengguna yang login, bukan dari form
    protected $fillable = ['report_id', 'pengklaim', 'bukti', 'catatan', 'status', 'alasan'];

    /** Relasi kebalikan: setiap klaim milik satu laporan barang. */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /** Pemilik klaim (1 user : N claims). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function kode(): Attribute
    {
        return Attribute::get(fn () => 'KL-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT));
    }

    /** Klaim masih menunggu keputusan petugas (pemilik masih boleh mengubah, lihat ClaimPolicy). */
    public function masihMenunggu(): bool
    {
        return $this->status === 'Menunggu';
    }
}
