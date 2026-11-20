<?php

namespace App\Http\Controllers;

use App\Support\DemoData;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    /** H-07 Laporan dan Klaim, pola master-detail (US-07, US-08, US-09) */
    public function index(Request $request)
    {
        $status = $request->query('status', 'Menunggu');
        $jenis = $request->query('jenis', '');

        $items = [];
        foreach (DemoData::reports() as $r) {
            $items[] = ['kode' => $r['kode'], 'tipe' => 'Laporan', 'jenis' => $r['jenis'], 'judul' => $r['jenis'] . ' - ' . $r['nama'], 'status' => $r['status']];
        }
        foreach (DemoData::claims() as $c) {
            $items[] = ['kode' => $c['kode'], 'tipe' => 'Klaim', 'jenis' => 'Klaim', 'judul' => 'Klaim - ' . DemoData::report($c['report_kode'])['nama'], 'status' => $c['status']];
        }

        $items = array_values(array_filter($items, function ($i) use ($status, $jenis) {
            $cocokStatus = $status === '' || $i['status'] === $status || ($status === 'Menunggu' && $i['status'] === 'Menunggu verifikasi');

            return $cocokStatus && ($jenis === '' || $i['jenis'] === $jenis);
        }));

        $dipilih = null;
        if ($kode = $request->query('kode')) {
            $dipilih = $this->detail($kode);
        }

        return view('pages.petugas', compact('items', 'dipilih', 'status', 'jenis'));
    }

    /** Simpan keputusan (simulasi): tolak wajib alasan (AC-08.2, AC-09.3); satu klaim disetujui per barang (AC-09.2) */
    public function keputusan(Request $request, string $kode)
    {
        $detail = $this->detail($kode);
        abort_unless($detail, 404);

        $data = $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'alasan' => ['required_if:aksi,tolak', 'nullable', 'string', 'max:500'],
        ], ['alasan.required_if' => 'Alasan wajib diisi saat menolak.']);

        if ($data['aksi'] === 'setujui' && ($detail['blokir'] ?? false)) {
            return back()->withErrors(['alasan' => 'Sudah ada klaim lain yang disetujui untuk barang ini.']);
        }

        $hasil = $data['aksi'] === 'setujui' ? ($detail['tipe'] === 'Klaim' ? 'Disetujui' : 'Terverifikasi') : 'Ditolak';

        return redirect()->route('petugas.index', ['status' => 'Menunggu'])
            ->with('sukses', "Keputusan untuk {$kode} tersimpan: {$hasil} (simulasi, belum tersimpan).");
    }

    private function detail(string $kode): ?array
    {
        foreach (DemoData::claims() as $c) {
            if ($c['kode'] === $kode) {
                $barang = DemoData::report($c['report_kode']);

                return ['tipe' => 'Klaim', 'klaim' => $c, 'barang' => $barang,
                    'blokir' => $c['status'] === 'Menunggu' && DemoData::sudahDiklaim($barang['kode'])];
            }
        }
        $r = DemoData::report($kode);

        return $r ? ['tipe' => 'Laporan', 'barang' => $r, 'blokir' => false] : null;
    }
}
