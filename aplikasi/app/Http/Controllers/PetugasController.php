<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/** H-07 Laporan dan Klaim (petugas), pola master-detail (US-07, US-08, US-09, US-10) */
class PetugasController extends Controller
{
    public function index(Request $request)
    {
        // "semua" dipakai sebagai nilai eksplisit: nilai kosong diubah Laravel menjadi null dan jatuh ke default
        $status = $request->query('status') ?: 'Menunggu';
        $status = $status === 'semua' ? '' : $status;
        $jenis = $request->query('jenis', '');

        $items = collect();
        if ($jenis !== 'Klaim') {
            Report::query()
                ->when($jenis, fn ($q) => $q->where('jenis', $jenis))
                ->when($status, fn ($q) => $status === 'Menunggu' ? $q->where('status', 'Menunggu verifikasi') : $q->where('status', $status))
                ->latest()->get()
                ->each(fn ($r) => $items->push(['tipe' => 'laporan', 'model' => $r, 'kode' => $r->kode, 'judul' => $r->jenis . ' - ' . $r->nama, 'status' => $r->status]));
        }
        if ($jenis === '' || $jenis === 'Klaim') {
            Claim::with('report')
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()->get()
                ->each(fn ($c) => $items->push(['tipe' => 'klaim', 'model' => $c, 'kode' => $c->kode, 'judul' => 'Klaim - ' . $c->report->nama, 'status' => $c->status]));
        }

        $dipilih = null;
        if ($kode = $request->query('kode')) {
            $dipilih = $this->cariDariKode($kode);
        }

        return view('pages.petugas', compact('items', 'dipilih', 'status', 'jenis'));
    }

    /** UPDATE status laporan: verifikasi atau tolak (AC-08.1, AC-08.2) */
    public function putuskanLaporan(Request $request, Report $report)
    {
        Gate::authorize('review', $report);
        $data = $this->validasiKeputusan($request);
        abort_unless($report->status === 'Menunggu verifikasi', 422, 'Laporan ini sudah diputuskan.');

        $report->update($data['aksi'] === 'setujui'
            ? ['status' => 'Terverifikasi', 'alasan' => null]
            : ['status' => 'Ditolak', 'alasan' => $data['alasan']]);

        return redirect()->route('petugas.index')->with('sukses', "Keputusan untuk {$report->kode} tersimpan: {$report->status}.");
    }

    /** UPDATE status klaim dengan aturan "satu klaim disetujui per barang" (AC-09.1 s.d. AC-09.3) */
    public function putuskanKlaim(Request $request, Claim $claim)
    {
        Gate::authorize('review', $claim);
        $data = $this->validasiKeputusan($request);
        abort_unless($claim->status === 'Menunggu', 422, 'Klaim ini sudah diputuskan.');

        if ($data['aksi'] === 'tolak') {
            $claim->update(['status' => 'Ditolak', 'alasan' => $data['alasan']]);

            return redirect()->route('petugas.index')->with('sukses', "Keputusan untuk {$claim->kode} tersimpan: Ditolak.");
        }

        // Transaksi + kunci baris mencegah dua petugas menyetujui klaim berbeda untuk barang yang sama
        $berhasil = DB::transaction(function () use ($claim) {
            $report = Report::whereKey($claim->report_id)->lockForUpdate()->first();
            if ($report->claims()->where('status', 'Disetujui')->exists()) {
                return false;
            }
            $claim->update(['status' => 'Disetujui', 'alasan' => null]);

            return true;
        });

        if (! $berhasil) {
            return back()->withErrors(['alasan' => 'Sudah ada klaim lain yang disetujui untuk barang ini.']);
        }

        return redirect()->route('petugas.index')->with('sukses', "Keputusan untuk {$claim->kode} tersimpan: Disetujui.");
    }

    /** UPDATE: catat serah terima, barang berstatus Dikembalikan (US-10) */
    public function serahTerima(Claim $claim)
    {
        Gate::authorize('review', $claim);
        abort_unless($claim->status === 'Disetujui', 422, 'Hanya klaim yang disetujui yang dapat diserahterimakan.');
        $claim->report->update(['status' => 'Dikembalikan']);

        return redirect()->route('petugas.index', ['status' => 'Disetujui'])
            ->with('sukses', "Barang {$claim->report->kode} dicatat sudah dikembalikan kepada {$claim->pengklaim}.");
    }

    private function validasiKeputusan(Request $request): array
    {
        return $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'alasan' => ['required_if:aksi,tolak', 'nullable', 'string', 'max:500'],
        ], ['alasan.required_if' => 'Alasan wajib diisi saat menolak.']);
    }

    private function cariDariKode(string $kode): ?array
    {
        $id = (int) substr($kode, 3);
        if (str_starts_with($kode, 'KL-') && $c = Claim::with('report.claims')->find($id)) {
            return ['tipe' => 'klaim', 'klaim' => $c, 'barang' => $c->report,
                'blokir' => $c->status === 'Menunggu' && $c->report->sudahDiklaim()];
        }
        if (str_starts_with($kode, 'LP-') && $r = Report::find($id)) {
            return ['tipe' => 'laporan', 'barang' => $r, 'blokir' => false];
        }

        return null;
    }
}
