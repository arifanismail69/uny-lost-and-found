<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

/** Halaman publik barang temuan: beranda, pencarian, detail. */
class BarangController extends Controller
{
    /** H-02 Beranda / Cari */
    public function beranda()
    {
        return view('pages.beranda', [
            'terbaru' => Report::publik()->with('claims')->latest('tanggal')->take(4)->get(),
            'kategori' => Report::KATEGORI,
            'lokasi' => Report::LOKASI,
        ]);
    }

    /** H-03 Hasil Pencarian (AC-04.1 s.d. AC-04.3, AC-04.5) */
    public function cari(Request $request)
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'in:' . implode(',', Report::KATEGORI)],
            'lokasi' => ['nullable', 'in:' . implode(',', Report::LOKASI)],
            'sejak' => ['nullable', 'date'],
        ]);

        $hasil = Report::publik()->with('claims')
            ->when($filter['q'] ?? null, fn ($q, $kata) => $q->where(fn ($w) => $w
                ->where('nama', 'like', "%{$kata}%")->orWhere('ciri', 'like', "%{$kata}%")->orWhere('kategori', 'like', "%{$kata}%")))
            ->when($filter['kategori'] ?? null, fn ($q, $v) => $q->where('kategori', $v))
            ->when($filter['lokasi'] ?? null, fn ($q, $v) => $q->where('lokasi', $v))
            ->when($filter['sejak'] ?? null, fn ($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->latest('tanggal')
            ->get()
            // barang yang masih bisa diklaim tampil lebih dulu (temuan walkthrough P3)
            ->sortBy(fn (Report $r) => $r->sudahDiklaim() ? 1 : 0)
            ->values();

        return view('pages.hasil', ['hasil' => $hasil, 'filter' => array_filter($filter)]);
    }

    /** H-04 Detail Barang berdasarkan ID + panel klaim (AC-04.4, AC-05.3) */
    public function show(Report $report)
    {
        abort_unless($report->isPublik(), 404);

        return view('pages.detail', [
            'barang' => $report,
            'sudahDiklaim' => $report->sudahDiklaim(),
        ]);
    }
}
