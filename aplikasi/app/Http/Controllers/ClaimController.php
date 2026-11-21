<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/** CRUD klaim kepemilikan. Akses per data diatur ClaimPolicy. */
class ClaimController extends Controller
{
    /** CREATE: kirim klaim dari halaman detail barang (AC-05.1 s.d. AC-05.4) */
    public function store(Request $request, Report $report)
    {
        Gate::authorize('create', Claim::class);
        abort_unless($report->isPublik(), 404);

        $data = $this->validasi($request);

        // Aturan bisnis 1: barang yang sudah punya klaim disetujui tidak menerima klaim baru
        if ($report->sudahDiklaim()) {
            return back()->withErrors(['bukti' => 'Barang sudah diklaim. Klaim baru tidak dapat diajukan.'])->withInput();
        }

        // Aturan bisnis 2: satu pengguna tidak boleh punya dua klaim aktif untuk barang yang sama
        $ganda = $report->claims()->where('user_id', $request->user()->id)->where('status', 'Menunggu')->exists();
        if ($ganda) {
            return back()->withErrors(['bukti' => 'Anda sudah mengajukan klaim untuk barang ini. Tunggu keputusan petugas.'])->withInput();
        }

        // user_id diambil dari pengguna yang login, tidak pernah dari input form
        $claim = $report->claims()->make($data + ['pengklaim' => $request->user()->name, 'status' => 'Menunggu']);
        $claim->user()->associate($request->user());
        $claim->save();

        return redirect()->route('riwayat')
            ->with('sukses', "Klaim {$claim->kode} untuk {$report->nama} tersimpan dengan status Menunggu.");
    }

    /** UPDATE (form) */
    public function edit(Claim $claim)
    {
        Gate::authorize('update', $claim);

        return view('klaim.edit', ['claim' => $claim->load('report')]);
    }

    /** UPDATE (simpan) */
    public function update(Request $request, Claim $claim)
    {
        Gate::authorize('update', $claim);
        $claim->update($this->validasi($request));

        return redirect()->route('riwayat')->with('sukses', "Klaim {$claim->kode} diperbarui.");
    }

    /** DELETE: batalkan klaim */
    public function destroy(Claim $claim)
    {
        Gate::authorize('delete', $claim);
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
}
