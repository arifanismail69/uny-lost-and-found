<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class RiwayatController extends Controller
{
    /** H-06 Riwayat (US-06). Pengguna simulasi: "dina". */
    public function __invoke()
    {
        $laporan = array_values(array_filter(DemoData::reports(), fn ($r) => $r['pelapor'] === 'dina'));
        $klaim = array_map(function ($c) {
            $c['barang'] = DemoData::report($c['report_kode']);

            return $c;
        }, array_values(array_filter(DemoData::claims(), fn ($c) => $c['pemilik'] === 'dina')));

        return view('pages.riwayat', compact('laporan', 'klaim'));
    }
}
