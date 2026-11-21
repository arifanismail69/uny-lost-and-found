<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Support\PenggunaSimulasi;
use Illuminate\Http\Request;

/**
 * CRUD laporan barang (padanan CRUD Ruang pada modul).
 * index = kelola laporan (petugas); create/store/edit/update/destroy = pelapor dan petugas.
 */
class ReportController extends Controller
{
    /** READ: daftar semua laporan dari database */
    public function index(Request $request)
    {
        $reports = Report::withCount('claims')
            ->when($request->query('jenis'), fn ($q, $v) => $q->where('jenis', $v))
            ->when($request->query('status'), fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->get();

        return view('laporan.index', [
            'reports' => $reports,
            'jenis' => $request->query('jenis', ''),
            'status' => $request->query('status', ''),
        ]);
    }

    /** CREATE (form) - H-05 Form Lapor Barang */
    public function create(Request $request)
    {
        return view('laporan.form', [
            'report' => new Report(['jenis' => $request->query('jenis') === 'Hilang' ? 'Hilang' : 'Temuan']),
        ]);
    }

    /** CREATE (simpan) */
    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['pelapor'] = PenggunaSimulasi::NAMA;
        $data['status'] = 'Menunggu verifikasi';

        $report = Report::create($data);

        return redirect()->route('riwayat')
            ->with('sukses', "Laporan {$report->kode} tersimpan dengan status Menunggu verifikasi.");
    }

    /** UPDATE (form) */
    public function edit(Request $request, Report $report)
    {
        $this->pastikanBolehUbah($request, $report);

        return view('laporan.form', ['report' => $report, 'dariPetugas' => $request->boolean('petugas')]);
    }

    /** UPDATE (simpan) */
    public function update(Request $request, Report $report)
    {
        $this->pastikanBolehUbah($request, $report);
        $report->update($this->validasi($request));

        return $request->boolean('petugas')
            ? redirect()->route('laporan.index')->with('sukses', "Laporan {$report->kode} diperbarui.")
            : redirect()->route('riwayat')->with('sukses', "Laporan {$report->kode} diperbarui.");
    }

    /** DELETE */
    public function destroy(Request $request, Report $report)
    {
        $this->pastikanBolehUbah($request, $report);
        $kode = $report->kode;
        $report->delete(); // klaim terkait ikut terhapus (cascadeOnDelete)

        return $request->boolean('petugas')
            ? redirect()->route('laporan.index')->with('sukses', "Laporan {$kode} dihapus.")
            : redirect()->route('riwayat')->with('sukses', "Laporan {$kode} dihapus.");
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:' . implode(',', Report::JENIS)],
            'nama' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:' . implode(',', Report::KATEGORI)],
            'ciri' => ['required', 'string', 'max:1000'],
            'ciri_rahasia' => ['nullable', 'string', 'max:500'],
            'lokasi' => ['required', 'in:' . implode(',', Report::LOKASI)],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'dititipkan_di' => ['required_if:jenis,Temuan', 'nullable', 'string', 'max:100'],
        ], [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'dititipkan_di.required_if' => 'Tempat penitipan wajib diisi untuk barang temuan.',
        ], [
            'nama' => 'Nama barang', 'kategori' => 'Kategori', 'ciri' => 'Ciri barang',
            'lokasi' => 'Lokasi', 'tanggal' => 'Tanggal', 'jenis' => 'Jenis laporan',
        ]);

        unset($data['foto']); // penyimpanan berkas foto belum dibuat (lihat dokumen keputusan)
        if ($data['jenis'] === 'Hilang') {
            $data['dititipkan_di'] = null;
        }

        return $data;
    }

    /** Pelapor hanya boleh mengubah laporannya sendiri selama belum diverifikasi; petugas boleh selalu. */
    private function pastikanBolehUbah(Request $request, Report $report): void
    {
        if ($request->boolean('petugas')) {
            return; // hak akses petugas dibatasi dengan login pada Pertemuan 6
        }

        abort_unless($report->pelapor === PenggunaSimulasi::NAMA && $report->bisaDiubahPelapor(), 403,
            'Laporan ini tidak dapat diubah lagi.');
    }
}
