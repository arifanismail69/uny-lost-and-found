<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Isi data awal aplikasi: php artisan db:seed */
    public function run(): void
    {
        $this->call(ReportSeeder::class);
    }
}
