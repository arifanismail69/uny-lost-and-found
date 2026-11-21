<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;

/** Dashboard setelah login: ringkasan milik sendiri (pengguna) atau ringkasan seluruh data (admin). */
class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $ringkasan = $user->isAdmin()
            ? [
                ['Laporan menunggu verifikasi', Report::where('status', 'Menunggu verifikasi')->count(), 'clock', route('petugas.index')],
                ['Klaim menunggu keputusan', Claim::where('status', 'Menunggu')->count(), 'shield', route('petugas.index', ['jenis' => 'Klaim'])],
                ['Barang siap diserahkan', Claim::where('status', 'Disetujui')->whereHas('report', fn ($q) => $q->where('status', '!=', 'Dikembalikan'))->count(), 'box', route('petugas.index', ['status' => 'Disetujui'])],
                ['Total laporan', Report::count(), 'list', route('laporan.index')],
            ]
            : [
                ['Laporan saya', $user->reports()->count(), 'list', route('riwayat')],
                ['Laporan menunggu verifikasi', $user->reports()->where('status', 'Menunggu verifikasi')->count(), 'clock', route('riwayat')],
                ['Klaim menunggu keputusan', $user->claims()->where('status', 'Menunggu')->count(), 'shield', route('riwayat')],
                ['Klaim disetujui', $user->claims()->where('status', 'Disetujui')->count(), 'check', route('riwayat')],
            ];

        return view('dashboard', compact('ringkasan'));
    }
}
