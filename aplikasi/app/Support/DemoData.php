<?php

namespace App\Support;

/**
 * Data contoh Pertemuan 4 (simulasi, belum memakai basis data).
 * Nama field sengaja disamakan dengan rencana tabel Pertemuan 5:
 * reports (kode, jenis, nama, kategori, lokasi, tanggal, ciri, ciri_rahasia, dititipkan_di, status, alasan)
 * claims  (kode, report_kode, pemilik, bukti, status, alasan)
 */
class DemoData
{
    public const KATEGORI = ['Dompet', 'Tas', 'Botol', 'Kunci', 'Payung', 'Elektronik', 'Lainnya'];

    public const LOKASI = [
        'Perpustakaan Pusat', 'Perpustakaan lt.1', 'Perpustakaan lt.2', 'Kantin FT',
        'Parkir Barat', 'Masjid Kampus', 'Gedung Kuliah Bersama', 'Ruang Dosen',
    ];

    public static function reports(): array
    {
        return [
            ['kode' => 'LP-001', 'jenis' => 'Temuan', 'nama' => 'Dompet kulit hitam', 'kategori' => 'Dompet', 'lokasi' => 'Perpustakaan Pusat', 'tanggal' => '2026-11-18', 'ciri' => 'Warna hitam, ada gantungan kunci kecil.', 'ciri_rahasia' => 'Foto keluarga di slot kartu dan dua lembar uang Rp50.000.', 'dititipkan_di' => 'Pos Satpam Gedung FT', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-002', 'jenis' => 'Temuan', 'nama' => 'Dompet cokelat', 'kategori' => 'Dompet', 'lokasi' => 'Perpustakaan lt.1', 'tanggal' => '2026-11-17', 'ciri' => 'Cokelat tua, ada resleting.', 'ciri_rahasia' => 'Kartu mahasiswa fakultas lain di dalamnya.', 'dititipkan_di' => 'Pos Satpam Gedung FT', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-003', 'jenis' => 'Temuan', 'nama' => 'Botol minum biru', 'kategori' => 'Botol', 'lokasi' => 'Kantin FT', 'tanggal' => '2026-11-17', 'ciri' => 'Biru tua, stiker bintang.', 'ciri_rahasia' => 'Inisial nama di bawah botol.', 'dititipkan_di' => 'Pos Satpam Gedung FT', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-004', 'jenis' => 'Temuan', 'nama' => 'Kunci motor Honda', 'kategori' => 'Kunci', 'lokasi' => 'Parkir Barat', 'tanggal' => '2026-11-16', 'ciri' => 'Gantungan karakter kucing.', 'ciri_rahasia' => 'Ada dua kunci cadangan.', 'dititipkan_di' => 'Pos Parkir Barat', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-005', 'jenis' => 'Temuan', 'nama' => 'Payung lipat hijau', 'kategori' => 'Payung', 'lokasi' => 'Masjid Kampus', 'tanggal' => '2026-11-16', 'ciri' => 'Hijau tua, gagang hitam.', 'ciri_rahasia' => 'Tulisan tangan di sarungnya.', 'dititipkan_di' => 'Sekretariat Masjid', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-006', 'jenis' => 'Temuan', 'nama' => 'Dompet hitam polos', 'kategori' => 'Dompet', 'lokasi' => 'Perpustakaan lt.2', 'tanggal' => '2026-11-10', 'ciri' => 'Hitam polos.', 'ciri_rahasia' => 'Berisi kartu ATM bank tertentu.', 'dititipkan_di' => 'Pos Satpam Gedung FT', 'status' => 'Terverifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-007', 'jenis' => 'Temuan', 'nama' => 'Tas ransel biru', 'kategori' => 'Tas', 'lokasi' => 'Gedung Kuliah Bersama', 'tanggal' => '2026-11-08', 'ciri' => 'Biru dongker.', 'ciri_rahasia' => 'Buku catatan bertanda nama.', 'dititipkan_di' => 'Pos Satpam GKB', 'status' => 'Dikembalikan', 'pelapor' => 'raka'],
            ['kode' => 'LP-008', 'jenis' => 'Temuan', 'nama' => 'Laptop abu-abu', 'kategori' => 'Elektronik', 'lokasi' => 'Ruang Dosen', 'tanggal' => '2026-11-19', 'ciri' => 'Abu-abu, stiker kampus.', 'ciri_rahasia' => 'Wallpaper bergambar pantai.', 'dititipkan_di' => 'Ruang Dosen', 'status' => 'Menunggu verifikasi', 'pelapor' => 'raka'],
            ['kode' => 'LP-009', 'jenis' => 'Temuan', 'nama' => 'Kunci loker', 'kategori' => 'Kunci', 'lokasi' => 'Perpustakaan lt.1', 'tanggal' => '2026-11-12', 'ciri' => 'Kunci kecil nomor 14.', 'ciri_rahasia' => '', 'dititipkan_di' => 'Perpustakaan', 'status' => 'Ditolak', 'alasan' => 'Foto tidak jelas', 'pelapor' => 'dina'],
            ['kode' => 'LP-010', 'jenis' => 'Hilang', 'nama' => 'Tas ransel biru', 'kategori' => 'Tas', 'lokasi' => 'Gedung Kuliah Bersama', 'tanggal' => '2026-11-08', 'ciri' => 'Biru dongker, ada gantungan.', 'ciri_rahasia' => 'Buku catatan bertanda nama.', 'dititipkan_di' => '', 'status' => 'Terverifikasi', 'pelapor' => 'dina'],
        ];
    }

    public static function claims(): array
    {
        return [
            ['kode' => 'KL-001', 'report_kode' => 'LP-005', 'pemilik' => 'dina', 'bukti' => 'Payung hijau milik saya.', 'status' => 'Ditolak', 'alasan' => 'Ciri tidak sesuai', 'tanggal' => '2026-11-09'],
            ['kode' => 'KL-002', 'report_kode' => 'LP-001', 'pemilik' => 'rani', 'bukti' => 'Ada foto keluarga dan uang lima puluh ribu dua lembar.', 'status' => 'Menunggu', 'tanggal' => '2026-11-19'],
            ['kode' => 'KL-003', 'report_kode' => 'LP-007', 'pemilik' => 'dina', 'bukti' => 'Ada buku catatan bertanda nama saya.', 'status' => 'Disetujui', 'tanggal' => '2026-11-14'],
            ['kode' => 'KL-004', 'report_kode' => 'LP-006', 'pemilik' => 'rani', 'bukti' => 'Berisi kartu ATM bank tertentu.', 'status' => 'Disetujui', 'tanggal' => '2026-11-11'],
            ['kode' => 'KL-005', 'report_kode' => 'LP-006', 'pemilik' => 'budi', 'bukti' => 'Dompet hitam saya, isinya kartu.', 'status' => 'Menunggu', 'tanggal' => '2026-11-19'],
        ];
    }

    public static function report(string $kode): ?array
    {
        foreach (self::reports() as $r) {
            if ($r['kode'] === $kode) {
                return $r;
            }
        }

        return null;
    }

    /** Satu barang hanya boleh punya satu klaim yang disetujui (AC-09.2). */
    public static function sudahDiklaim(string $kode): bool
    {
        foreach (self::claims() as $c) {
            if ($c['report_kode'] === $kode && $c['status'] === 'Disetujui') {
                return true;
            }
        }

        return false;
    }

    /** Barang yang boleh tampil di pencarian publik (AC-04.3, AC-04.5). */
    public static function publik(): array
    {
        return array_values(array_filter(self::reports(), fn ($r) => $r['jenis'] === 'Temuan' && $r['status'] === 'Terverifikasi'));
    }
}
