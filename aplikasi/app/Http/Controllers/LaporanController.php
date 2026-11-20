<?php

namespace App\Http\Controllers;

use App\Support\DemoData;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /** H-05 Form Lapor Barang (US-02, US-03) */
    public function create(Request $request)
    {
        return view('pages.lapor', [
            'jenis' => $request->query('jenis') === 'Hilang' ? 'Hilang' : 'Temuan',
            'kategori' => DemoData::KATEGORI,
            'lokasi' => DemoData::LOKASI,
        ]);
    }

    /** Simpan laporan (simulasi): validasi sesuai AC-02.2, AC-02.3, AC-03.2, AC-03.4 */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => ['required', 'in:Hilang,Temuan'],
            'nama' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:' . implode(',', DemoData::KATEGORI)],
            'ciri' => ['required', 'string', 'max:1000'],
            'ciri_rahasia' => ['nullable', 'string', 'max:500'],
            'lokasi' => ['required', 'in:' . implode(',', DemoData::LOKASI)],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'dititipkan_di' => ['required_if:jenis,Temuan', 'nullable', 'string', 'max:100'],
        ], [
            'required' => ':attribute wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'dititipkan_di.required_if' => 'Tempat penitipan wajib diisi untuk barang temuan.',
        ], [
            'nama' => 'Nama barang', 'kategori' => 'Kategori', 'ciri' => 'Ciri barang',
            'lokasi' => 'Lokasi', 'tanggal' => 'Tanggal', 'jenis' => 'Jenis laporan',
        ]);

        return redirect()->route('riwayat')
            ->with('sukses', 'Laporan terkirim dengan status Menunggu verifikasi (simulasi, belum tersimpan).');
    }
}
