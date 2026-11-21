<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * CRUD laporan barang. Akses area diatur middleware di routes/web.php,
 * akses per data diatur ReportPolicy lewat Gate::authorize().
 */
class ReportController extends Controller
{
    /** READ: semua laporan (Kelola Laporan, khusus admin) */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Report::class);

        $reports = Report::with('user')->withCount('claims')
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

    /** CREATE (form) */
    public function create(Request $request)
    {
        Gate::authorize('create', Report::class);

        return view('laporan.form', [
            'report' => new Report(['jenis' => $request->query('jenis') === 'Hilang' ? 'Hilang' : 'Temuan']),
        ]);
    }

    /** CREATE (simpan): pemilik diambil dari pengguna yang login, bukan dari form */
    public function store(Request $request)
    {
        Gate::authorize('create', Report::class);

        $report = new Report($this->validasi($request) + [
            'pelapor' => $request->user()->name,
            'status' => 'Menunggu verifikasi',
        ]);
        $report->user()->associate($request->user());
        $report->save();

        return redirect()->route('riwayat')
            ->with('sukses', "Laporan {$report->kode} tersimpan dengan status Menunggu verifikasi.");
    }

    /** UPDATE (form) */
    public function edit(Report $report)
    {
        Gate::authorize('update', $report);

        return view('laporan.form', ['report' => $report]);
    }

    /** UPDATE (simpan) */
    public function update(Request $request, Report $report)
    {
        Gate::authorize('update', $report);
        $report->update($this->validasi($request));

        return $this->kembali($request, "Laporan {$report->kode} diperbarui.");
    }

    /** DELETE */
    public function destroy(Request $request, Report $report)
    {
        Gate::authorize('delete', $report);
        $kode = $report->kode;
        $report->delete(); // klaim terkait ikut terhapus (cascadeOnDelete)

        return $this->kembali($request, "Laporan {$kode} dihapus.");
    }

    private function kembali(Request $request, string $pesan)
    {
        return redirect()->route($request->user()->isAdmin() ? 'laporan.index' : 'riwayat')->with('sukses', $pesan);
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

        unset($data['foto']); // penyimpanan berkas foto belum dibuat
        if ($data['jenis'] === 'Hilang') {
            $data['dititipkan_di'] = null;
        }

        return $data;
    }
}
