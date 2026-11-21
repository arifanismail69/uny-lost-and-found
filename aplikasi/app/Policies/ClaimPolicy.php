<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;

/** Otorisasi tingkat data untuk klaim kepemilikan (padanan BookingPolicy pada modul). */
class ClaimPolicy
{
    /** Mengajukan klaim: hanya pengguna biasa; petugas tidak mengklaim barang. */
    public function create(User $user): bool
    {
        return ! $user->isAdmin();
    }

    /** Mengubah: admin, atau pemilik selama klaim masih menunggu. */
    public function update(User $user, Claim $claim): bool
    {
        return $user->isAdmin()
            || ($claim->user_id === $user->id && $claim->masihMenunggu());
    }

    public function delete(User $user, Claim $claim): bool
    {
        return $this->update($user, $claim);
    }

    /** Menyetujui, menolak, dan mencatat serah terima: hanya admin. */
    public function review(User $user, Claim $claim): bool
    {
        return $user->isAdmin();
    }
}
