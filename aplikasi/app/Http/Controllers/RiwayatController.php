<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    /** H-06 Riwayat: hanya laporan dan klaim milik pengguna yang login */
    public function __invoke(Request $request)
    {
        return view('pages.riwayat', [
            'laporan' => $request->user()->reports()->latest()->get(),
            'klaim' => $request->user()->claims()->with('report')->latest()->get(),
        ]);
    }
}
