<?php

namespace App\Http\Controllers;

use App\Support\DemoData;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /** H-02 Beranda / Cari */
    public function beranda()
    {
        return view('pages.beranda', [
            'terbaru' => array_slice(DemoData::publik(), 0, 4),
            'kategori' => DemoData::KATEGORI,
            'lokasi' => DemoData::LOKASI,
        ]);
    }

    /** H-03 Hasil Pencarian (AC-04.1 s.d. AC-04.3, AC-04.5) */
    public function cari(Request $request)
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'in:' . implode(',', DemoData::KATEGORI)],
            'lokasi' => ['nullable', 'in:' . implode(',', DemoData::LOKASI)],
            'sejak' => ['nullable', 'date'],
        ]);

        $hasil = array_filter(DemoData::publik(), function ($r) use ($filter) {
            $teks = mb_strtolower($r['nama'] . ' ' . $r['ciri'] . ' ' . $r['kategori']);

            return (empty($filter['q']) || str_contains($teks, mb_strtolower($filter['q'])))
                && (empty($filter['kategori']) || $r['kategori'] === $filter['kategori'])
                && (empty($filter['lokasi']) || $r['lokasi'] === $filter['lokasi'])
                && (empty($filter['sejak']) || $r['tanggal'] >= $filter['sejak']);
        });

        // Barang yang masih dapat diklaim ditampilkan lebih dulu (temuan walkthrough P3 no. 4)
        usort($hasil, fn ($a, $b) => DemoData::sudahDiklaim($a['kode']) <=> DemoData::sudahDiklaim($b['kode']));

        return view('pages.hasil', ['hasil' => $hasil, 'filter' => array_filter($filter)]);
    }

    /** H-04 Detail Barang + panel klaim (AC-04.4, AC-05.3) */
    public function show(string $kode)
    {
        $barang = DemoData::report($kode);
        $publik = $barang && $barang['jenis'] === 'Temuan' && $barang['status'] === 'Terverifikasi';

        if (! $publik) {
            abort(404);
        }

        return view('pages.detail', [
            'barang' => $barang,
            'sudahDiklaim' => DemoData::sudahDiklaim($kode),
        ]);
    }

    /** Kirim klaim (simulasi): validasi bukti, lalu ke Riwayat (AC-05.1, AC-05.2) */
    public function klaim(Request $request, string $kode)
    {
        abort_unless(DemoData::report($kode), 404);

        if (DemoData::sudahDiklaim($kode)) {
            return back()->withErrors(['bukti' => 'Barang sudah diklaim. Klaim baru tidak dapat diajukan.']);
        }

        $request->validate([
            'bukti' => ['required', 'string', 'min:10', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'bukti.required' => 'Ciri pembuktian wajib diisi.',
            'bukti.min' => 'Jelaskan ciri pembuktian minimal 10 karakter.',
        ]);

        return redirect()->route('riwayat')
            ->with('sukses', 'Klaim untuk barang ' . $kode . ' terkirim dengan status Menunggu (simulasi, belum tersimpan).');
    }
}
