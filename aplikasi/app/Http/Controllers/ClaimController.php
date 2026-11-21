<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use App\Support\PenggunaSimulasi;
use Illuminate\Http\Request;

/** CRUD klaim kepemilikan (padanan CRUD Pengajuan pada modul). Daftar klaim tampil di Riwayat dan panel petugas. */
class ClaimController extends Controller
{
    /** CREATE: kirim klaim dari halaman detail barang (AC-05.1 s.d. AC-05.4) */
    public function store(Request $request, Report $report)
    {
        abort_unless($report->isPublik(), 404);

        $data = $this->validasi($request);

        // Aturan bisnis 1: barang yang sudah punya klaim disetujui tidak menerima klaim baru
        if ($report->sudahDiklaim()) {
            return back()->withErrors(['bukti' => 'Barang sudah diklaim. Klaim baru tidak dapat diajukan.'])->withInput();
        }

        // Aturan bisnis 2: satu pengklaim tidak boleh punya dua klaim aktif untuk barang yang sama
        $ganda = $report->claims()->where('pengklaim', PenggunaSimulasi::NAMA)->where('status', 'Menunggu')->exists();
        if ($ganda) {
            return back()->withErrors(['bukti' => 'Anda sudah mengajukan klaim untuk barang ini. Tunggu keputusan petugas.'])->withInput();
        }

        $claim = $report->claims()->create($data + ['pengklaim' => PenggunaSimulasi::NAMA, 'status' => 'Menunggu']);

        return redirect()->route('riwayat')
            ->with('sukses', "Klaim {$claim->kode} untuk {$report->nama} tersimpan dengan status Menunggu.");
    }

    /** UPDATE (form): ubah bukti selama klaim masih menunggu */
    public function edit(Claim $claim)
    {
        $this->pastikanMilikSendiri($claim);

        return view('klaim.edit', ['claim' => $claim->load('report')]);
    }

    /** UPDATE (simpan) */
    public function update(Request $request, Claim $claim)
    {
        $this->pastikanMilikSendiri($claim);
        $claim->update($this->validasi($request));

        return redirect()->route('riwayat')->with('sukses', "Klaim {$claim->kode} diperbarui.");
    }

    /** DELETE: batalkan klaim */
    public function destroy(Claim $claim)
    {
        $this->pastikanMilikSendiri($claim);
        $kode = $claim->kode;
        $claim->delete();

        return redirect()->route('riwayat')->with('sukses', "Klaim {$kode} dibatalkan.");
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'bukti' => ['required', 'string', 'min:10', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'bukti.required' => 'Ciri pembuktian wajib diisi.',
            'bukti.min' => 'Jelaskan ciri pembuktian minimal 10 karakter.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ]);
    }

    private function pastikanMilikSendiri(Claim $claim): void
    {
        abort_unless($claim->pengklaim === PenggunaSimulasi::NAMA && $claim->bisaDiubahPengklaim(), 403,
            'Klaim ini tidak dapat diubah lagi.');
    }
}
