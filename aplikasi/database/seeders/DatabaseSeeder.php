<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Isi data awal: php artisan migrate:fresh --seed */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,   // akun harus ada lebih dulu agar laporan/klaim punya pemilik
            ReportSeeder::class,
        ]);
    }
}
