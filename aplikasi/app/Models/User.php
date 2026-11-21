<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Catatan keamanan: "role" sengaja TIDAK dimasukkan ke Fillable, sehingga tidak bisa diisi
 * dari form (misalnya registrasi yang disisipi role=admin). Peran diatur lewat seeder atau
 * oleh admin dengan forceFill().
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';       // petugas keamanan / pos informasi

    public const ROLE_PENGGUNA = 'pengguna'; // civitas: pelapor dan pengklaim

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Laporan yang dibuat pengguna ini (1 user : N reports). */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /** Klaim yang diajukan pengguna ini (1 user : N claims). */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}
