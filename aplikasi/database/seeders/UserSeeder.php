<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Akun uji. Password "password" hanya untuk latihan di komputer sendiri,
 * jangan dipakai pada server sungguhan.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            ['admin@lostfound.test', 'Pak Slamet', User::ROLE_ADMIN],
            ['dina@lostfound.test', 'Dina', User::ROLE_PENGGUNA],
            ['rani@lostfound.test', 'Rani', User::ROLE_PENGGUNA],
            ['budi@lostfound.test', 'Budi', User::ROLE_PENGGUNA],
            ['raka@lostfound.test', 'Raka', User::ROLE_PENGGUNA],
        ];

        foreach ($akun as [$email, $nama, $peran]) {
            $user = User::updateOrCreate(['email' => $email], ['name' => $nama, 'password' => Hash::make('password')]);
            // role diisi dengan forceFill karena sengaja tidak termasuk Fillable
            $user->forceFill(['role' => $peran, 'email_verified_at' => $user->email_verified_at ?? now()])->save();
        }
    }
}
