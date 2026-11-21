<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use App\Support\PenggunaSimulasi;

class RiwayatController extends Controller
{
    /** H-06 Riwayat (US-06): laporan dan klaim milik pengguna, nama barang dibaca lewat relasi */
    public function __invoke()
    {
        return view('pages.riwayat', [
            'laporan' => Report::where('pelapor', PenggunaSimulasi::NAMA)->latest()->get(),
            'klaim' => Claim::with('report')->where('pengklaim', PenggunaSimulasi::NAMA)->latest()->get(),
        ]);
    }
}
