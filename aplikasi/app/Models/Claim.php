<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    public const STATUS = ['Menunggu', 'Disetujui', 'Ditolak'];

    protected $fillable = ['report_id', 'pengklaim', 'bukti', 'catatan', 'status', 'alasan'];

    /** Relasi kebalikan: setiap klaim milik satu laporan barang. */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    protected function kode(): Attribute
    {
        return Attribute::get(fn () => 'KL-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT));
    }

    /** Pengklaim hanya boleh mengubah/membatalkan klaim yang masih menunggu. */
    public function bisaDiubahPengklaim(): bool
    {
        return $this->status === 'Menunggu';
    }
}
