<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

/** Otorisasi tingkat data untuk laporan barang. */
class ReportPolicy
{
    /** Daftar semua laporan (Kelola Laporan): hanya admin. */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /** Membuat laporan: semua pengguna yang login. */
    public function create(User $user): bool
    {
        return true;
    }

    /** Mengubah: admin, atau pemilik selama laporan belum diverifikasi. */
    public function update(User $user, Report $report): bool
    {
        return $user->isAdmin()
            || ($report->user_id === $user->id && $report->masihMenunggu());
    }

    public function delete(User $user, Report $report): bool
    {
        return $this->update($user, $report);
    }

    /** Memverifikasi atau menolak laporan: hanya admin. */
    public function review(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }
}
