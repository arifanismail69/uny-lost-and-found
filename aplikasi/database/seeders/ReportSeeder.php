<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Database\Seeder;

/** Data awal laporan dan klaim (dipindahkan dari data contoh Pertemuan 4). */
class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $laporan = [
            ['Temuan', 'Dompet kulit hitam', 'Dompet', 'Perpustakaan Pusat', '2026-11-18', 'Warna hitam, ada gantungan kunci kecil.', 'Foto keluarga di slot kartu dan dua lembar uang Rp50.000.', 'Pos Satpam Gedung FT', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Dompet cokelat', 'Dompet', 'Perpustakaan lt.1', '2026-11-17', 'Cokelat tua, ada resleting.', 'Kartu mahasiswa fakultas lain di dalamnya.', 'Pos Satpam Gedung FT', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Botol minum biru', 'Botol', 'Kantin FT', '2026-11-17', 'Biru tua, stiker bintang.', 'Inisial nama di bawah botol.', 'Pos Satpam Gedung FT', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Kunci motor Honda', 'Kunci', 'Parkir Barat', '2026-11-16', 'Gantungan karakter kucing.', 'Ada dua kunci cadangan.', 'Pos Parkir Barat', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Payung lipat hijau', 'Payung', 'Masjid Kampus', '2026-11-16', 'Hijau tua, gagang hitam.', 'Tulisan tangan di sarungnya.', 'Sekretariat Masjid', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Dompet hitam polos', 'Dompet', 'Perpustakaan lt.2', '2026-11-10', 'Hitam polos.', 'Berisi kartu ATM bank tertentu.', 'Pos Satpam Gedung FT', 'Terverifikasi', null, 'Raka'],
            ['Temuan', 'Tas ransel biru', 'Tas', 'Gedung Kuliah Bersama', '2026-11-08', 'Biru dongker.', 'Buku catatan bertanda nama.', 'Pos Satpam GKB', 'Dikembalikan', null, 'Raka'],
            ['Temuan', 'Laptop abu-abu', 'Elektronik', 'Ruang Dosen', '2026-11-19', 'Abu-abu, stiker kampus.', 'Wallpaper bergambar pantai.', 'Ruang Dosen', 'Menunggu verifikasi', null, 'Raka'],
            ['Temuan', 'Kunci loker', 'Kunci', 'Perpustakaan lt.1', '2026-11-12', 'Kunci kecil nomor 14.', null, 'Perpustakaan', 'Ditolak', 'Foto tidak jelas', 'Dina'],
            ['Hilang', 'Tas ransel biru', 'Tas', 'Gedung Kuliah Bersama', '2026-11-08', 'Biru dongker, ada gantungan.', 'Buku catatan bertanda nama.', null, 'Terverifikasi', null, 'Dina'],
        ];

        $r = [];
        foreach ($laporan as $i => $l) {
            $r[$i + 1] = Report::create(array_combine(
                ['jenis', 'nama', 'kategori', 'lokasi', 'tanggal', 'ciri', 'ciri_rahasia', 'dititipkan_di', 'status', 'alasan', 'pelapor'],
                $l,
            ));
        }

        $klaim = [
            [5, 'Dina', 'Payung hijau milik saya.', 'Ditolak', 'Ciri tidak sesuai'],
            [1, 'Rani', 'Ada foto keluarga dan uang lima puluh ribu dua lembar.', 'Menunggu', null],
            [7, 'Dina', 'Ada buku catatan bertanda nama saya.', 'Disetujui', null],
            [6, 'Rani', 'Berisi kartu ATM bank tertentu.', 'Disetujui', null],
            [6, 'Budi', 'Dompet hitam saya, isinya kartu.', 'Menunggu', null],
        ];

        foreach ($klaim as [$laporanKe, $pengklaim, $bukti, $status, $alasan]) {
            Claim::create([
                'report_id' => $r[$laporanKe]->id, 'pengklaim' => $pengklaim, 'bukti' => $bukti,
                'status' => $status, 'alasan' => $alasan,
            ]);
        }
    }
}
