<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Uji CRUD laporan dan klaim serta aturan bisnis Pertemuan 5 (data benar-benar berubah di database). */
class CrudPertemuan5Test extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function laporanValid(array $ubah = []): array
    {
        return array_merge([
            'jenis' => 'Temuan', 'nama' => 'Jam tangan hitam', 'kategori' => 'Lainnya', 'ciri' => 'Tali karet hitam',
            'ciri_rahasia' => 'Ada goresan di kaca', 'lokasi' => 'Kantin FT', 'tanggal' => now()->toDateString(),
            'dititipkan_di' => 'Pos Satpam Gedung FT',
        ], $ubah);
    }

    private function barang(string $nama): Report
    {
        return Report::where('nama', $nama)->where('jenis', 'Temuan')->firstOrFail();
    }

    // ---------- Model dan seeder ----------

    public function test_seeder_mengisi_data_dan_relasi_terbaca(): void
    {
        $this->assertSame(10, Report::count());
        $this->assertSame(5, Claim::count());
        $dompetPolos = $this->barang('Dompet hitam polos');
        $this->assertCount(2, $dompetPolos->claims);
        $this->assertSame('Dompet hitam polos', $dompetPolos->claims->first()->report->nama);
    }

    // ---------- CRUD laporan ----------

    public function test_create_laporan_tersimpan_di_database(): void
    {
        $this->post('/laporan', $this->laporanValid())->assertRedirect('/riwayat')->assertSessionHas('sukses');
        $this->assertDatabaseHas('reports', ['nama' => 'Jam tangan hitam', 'status' => 'Menunggu verifikasi', 'pelapor' => 'Dina']);
    }

    public function test_create_laporan_ditolak_jika_tidak_valid(): void
    {
        $this->post('/laporan', ['jenis' => 'Temuan', 'tanggal' => now()->addDay()->toDateString()])
            ->assertSessionHasErrors(['nama', 'kategori', 'ciri', 'lokasi', 'tanggal', 'dititipkan_di']);
        $this->assertSame(10, Report::count());
    }

    public function test_read_daftar_laporan_dari_database(): void
    {
        $this->get('/laporan')->assertOk()->assertSee('Laptop abu-abu')->assertSee('Kunci loker');
        $this->get('/laporan?status=Ditolak')->assertSee('Kunci loker')->assertDontSee('Laptop abu-abu');
    }

    public function test_update_laporan_oleh_pelapor_selama_menunggu(): void
    {
        $this->post('/laporan', $this->laporanValid());
        $laporan = Report::where('nama', 'Jam tangan hitam')->first();

        $this->put("/laporan/{$laporan->id}", $this->laporanValid(['nama' => 'Jam tangan hitam Casio']))->assertRedirect('/riwayat');
        $this->assertSame('Jam tangan hitam Casio', $laporan->fresh()->nama);
    }

    public function test_laporan_terverifikasi_tidak_bisa_diubah_pelapor_tetapi_bisa_oleh_petugas(): void
    {
        $tasHilang = Report::where('pelapor', 'Dina')->where('jenis', 'Hilang')->first();
        $this->put("/laporan/{$tasHilang->id}", $this->laporanValid(['jenis' => 'Hilang']))->assertForbidden();

        $this->put("/laporan/{$tasHilang->id}?petugas=1", $this->laporanValid(['jenis' => 'Hilang', 'nama' => 'Tas ransel biru dongker']))
            ->assertRedirect('/laporan');
        $this->assertSame('Tas ransel biru dongker', $tasHilang->fresh()->nama);
        $this->assertNull($tasHilang->fresh()->dititipkan_di);
    }

    public function test_delete_laporan_ikut_menghapus_klaimnya(): void
    {
        $dompetPolos = $this->barang('Dompet hitam polos');
        $this->delete("/laporan/{$dompetPolos->id}?petugas=1")->assertRedirect('/laporan');
        $this->assertDatabaseMissing('reports', ['id' => $dompetPolos->id]);
        $this->assertSame(0, Claim::where('report_id', $dompetPolos->id)->count());
    }

    // ---------- CRUD klaim ----------

    public function test_create_klaim_tersimpan_dan_tampil_di_riwayat_lewat_relasi(): void
    {
        $dompet = $this->barang('Dompet cokelat');
        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain'])->assertRedirect('/riwayat');
        $this->assertDatabaseHas('claims', ['report_id' => $dompet->id, 'pengklaim' => 'Dina', 'status' => 'Menunggu']);
        $this->get('/riwayat')->assertSee('Dompet cokelat');
    }

    public function test_klaim_ditolak_jika_bukti_kosong_ganda_atau_barang_sudah_diklaim(): void
    {
        $dompet = $this->barang('Dompet cokelat');
        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => ''])->assertSessionHasErrors('bukti');

        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain']);
        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Klaim kedua untuk barang sama'])->assertSessionHasErrors('bukti');

        $dompetPolos = $this->barang('Dompet hitam polos');
        $this->post("/barang/{$dompetPolos->id}/klaim", ['bukti' => 'Dompet saya juga sebenarnya'])->assertSessionHasErrors('bukti');

        $this->assertSame(1, Claim::where('report_id', $dompet->id)->count());
    }

    public function test_update_dan_delete_klaim_milik_sendiri(): void
    {
        $dompet = $this->barang('Dompet cokelat');
        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain']);
        $klaim = Claim::latest('id')->first();

        $this->put("/klaim/{$klaim->id}", ['bukti' => 'Ada kartu mahasiswa FMIPA atas nama saya'])->assertRedirect('/riwayat');
        $this->assertSame('Ada kartu mahasiswa FMIPA atas nama saya', $klaim->fresh()->bukti);

        $this->delete("/klaim/{$klaim->id}")->assertRedirect('/riwayat');
        $this->assertDatabaseMissing('claims', ['id' => $klaim->id]);
    }

    public function test_klaim_orang_lain_atau_yang_sudah_diputuskan_tidak_bisa_diubah(): void
    {
        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->put("/klaim/{$klaimRani->id}", ['bukti' => 'Mencoba mengubah klaim orang lain'])->assertForbidden();

        $klaimDinaDitolak = Claim::where('pengklaim', 'Dina')->where('status', 'Ditolak')->first();
        $this->delete("/klaim/{$klaimDinaDitolak->id}")->assertForbidden();
    }

    // ---------- Keputusan petugas dan aturan bisnis ----------

    public function test_petugas_memverifikasi_dan_menolak_laporan(): void
    {
        $laptop = $this->barang('Laptop abu-abu');
        $this->patch("/petugas/laporan/{$laptop->id}", ['aksi' => 'tolak'])->assertSessionHasErrors('alasan');
        $this->patch("/petugas/laporan/{$laptop->id}", ['aksi' => 'setujui'])->assertSessionHas('sukses');
        $this->assertSame('Terverifikasi', $laptop->fresh()->status);
        $this->get('/cari')->assertSee('Laptop abu-abu');
    }

    public function test_satu_barang_hanya_boleh_satu_klaim_disetujui(): void
    {
        $klaimBudi = Claim::where('pengklaim', 'Budi')->first(); // barangnya sudah punya klaim disetujui
        $this->patch("/petugas/klaim/{$klaimBudi->id}", ['aksi' => 'setujui'])->assertSessionHasErrors('alasan');
        $this->assertSame('Menunggu', $klaimBudi->fresh()->status);

        $this->patch("/petugas/klaim/{$klaimBudi->id}", ['aksi' => 'tolak', 'alasan' => 'Ciri tidak cocok']);
        $this->assertSame('Ditolak', $klaimBudi->fresh()->status);
        $this->assertSame('Ciri tidak cocok', $klaimBudi->fresh()->alasan);
    }

    public function test_menyetujui_klaim_lalu_serah_terima_mengubah_status_barang(): void
    {
        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->patch("/petugas/klaim/{$klaimRani->id}", ['aksi' => 'setujui'])->assertSessionHas('sukses');
        $this->assertSame('Disetujui', $klaimRani->fresh()->status);

        $this->patch("/petugas/klaim/{$klaimRani->id}/serah-terima")->assertSessionHas('sukses');
        $this->assertSame('Dikembalikan', $klaimRani->report->fresh()->status);
        $this->get('/cari')->assertDontSee('Dompet kulit hitam');
    }
}
