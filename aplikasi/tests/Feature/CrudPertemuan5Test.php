<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Uji CRUD laporan dan klaim serta aturan bisnis Pertemuan 5, kini dengan akun login (Pertemuan 6). */
class CrudPertemuan5Test extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function dina(): User
    {
        return User::where('email', 'dina@lostfound.test')->firstOrFail();
    }

    private function admin(): User
    {
        return User::where('email', 'admin@lostfound.test')->firstOrFail();
    }

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
        $this->assertSame(2, $this->dina()->reports()->count());
    }

    // ---------- CRUD laporan ----------

    public function test_create_laporan_tersimpan_dengan_pemilik_dari_akun_login(): void
    {
        $this->actingAs($this->dina())->post('/laporan', $this->laporanValid())->assertRedirect('/riwayat')->assertSessionHas('sukses');
        $this->assertDatabaseHas('reports', [
            'nama' => 'Jam tangan hitam', 'status' => 'Menunggu verifikasi', 'pelapor' => 'Dina', 'user_id' => $this->dina()->id,
        ]);
    }

    public function test_user_id_tidak_bisa_disisipkan_dari_form(): void
    {
        $this->actingAs($this->dina())->post('/laporan', $this->laporanValid(['user_id' => $this->admin()->id]));
        $this->assertSame($this->dina()->id, Report::latest('id')->first()->user_id);
    }

    public function test_create_laporan_ditolak_jika_tidak_valid(): void
    {
        $this->actingAs($this->dina())->post('/laporan', ['jenis' => 'Temuan', 'tanggal' => now()->addDay()->toDateString()])
            ->assertSessionHasErrors(['nama', 'kategori', 'ciri', 'lokasi', 'tanggal', 'dititipkan_di']);
        $this->assertSame(10, Report::count());
    }

    public function test_read_daftar_laporan_dari_database(): void
    {
        $this->actingAs($this->admin())->get('/laporan')->assertOk()->assertSee('Laptop abu-abu')->assertSee('Kunci loker');
        $this->actingAs($this->admin())->get('/laporan?status=Ditolak')->assertSee('Kunci loker')->assertDontSee('Laptop abu-abu');
    }

    public function test_update_laporan_oleh_pelapor_selama_menunggu(): void
    {
        $this->actingAs($this->dina())->post('/laporan', $this->laporanValid());
        $laporan = Report::where('nama', 'Jam tangan hitam')->first();

        $this->actingAs($this->dina())->put("/laporan/{$laporan->id}", $this->laporanValid(['nama' => 'Jam tangan hitam Casio']))
            ->assertRedirect('/riwayat');
        $this->assertSame('Jam tangan hitam Casio', $laporan->fresh()->nama);
    }

    public function test_laporan_terverifikasi_tidak_bisa_diubah_pelapor_tetapi_bisa_oleh_admin(): void
    {
        $tasHilang = Report::where('pelapor', 'Dina')->where('jenis', 'Hilang')->first();
        $this->actingAs($this->dina())->put("/laporan/{$tasHilang->id}", $this->laporanValid(['jenis' => 'Hilang']))->assertForbidden();

        $this->actingAs($this->admin())->put("/laporan/{$tasHilang->id}", $this->laporanValid(['jenis' => 'Hilang', 'nama' => 'Tas ransel biru dongker']))
            ->assertRedirect('/laporan');
        $this->assertSame('Tas ransel biru dongker', $tasHilang->fresh()->nama);
        $this->assertNull($tasHilang->fresh()->dititipkan_di);
    }

    public function test_delete_laporan_oleh_admin_ikut_menghapus_klaimnya(): void
    {
        $dompetPolos = $this->barang('Dompet hitam polos');
        $this->actingAs($this->admin())->delete("/laporan/{$dompetPolos->id}")->assertRedirect('/laporan');
        $this->assertDatabaseMissing('reports', ['id' => $dompetPolos->id]);
        $this->assertSame(0, Claim::where('report_id', $dompetPolos->id)->count());
    }

    // ---------- CRUD klaim ----------

    public function test_create_klaim_tersimpan_dan_tampil_di_riwayat_lewat_relasi(): void
    {
        $dompet = $this->barang('Dompet cokelat');
        $this->actingAs($this->dina())->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain'])->assertRedirect('/riwayat');
        $this->assertDatabaseHas('claims', ['report_id' => $dompet->id, 'user_id' => $this->dina()->id, 'status' => 'Menunggu']);
        $this->actingAs($this->dina())->get('/riwayat')->assertSee('Dompet cokelat');
    }

    public function test_klaim_ditolak_jika_bukti_kosong_ganda_atau_barang_sudah_diklaim(): void
    {
        $dina = $this->dina();
        $dompet = $this->barang('Dompet cokelat');
        $this->actingAs($dina)->post("/barang/{$dompet->id}/klaim", ['bukti' => ''])->assertSessionHasErrors('bukti');

        $this->actingAs($dina)->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain']);
        $this->actingAs($dina)->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Klaim kedua untuk barang sama'])->assertSessionHasErrors('bukti');

        $dompetPolos = $this->barang('Dompet hitam polos');
        $this->actingAs($dina)->post("/barang/{$dompetPolos->id}/klaim", ['bukti' => 'Dompet saya juga sebenarnya'])->assertSessionHasErrors('bukti');

        $this->assertSame(1, Claim::where('report_id', $dompet->id)->count());
    }

    public function test_update_dan_delete_klaim_milik_sendiri(): void
    {
        $dompet = $this->barang('Dompet cokelat');
        $this->actingAs($this->dina())->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Ada kartu mahasiswa fakultas lain']);
        $klaim = Claim::latest('id')->first();

        $this->actingAs($this->dina())->put("/klaim/{$klaim->id}", ['bukti' => 'Ada kartu mahasiswa FMIPA atas nama saya'])->assertRedirect('/riwayat');
        $this->assertSame('Ada kartu mahasiswa FMIPA atas nama saya', $klaim->fresh()->bukti);

        $this->actingAs($this->dina())->delete("/klaim/{$klaim->id}")->assertRedirect('/riwayat');
        $this->assertDatabaseMissing('claims', ['id' => $klaim->id]);
    }

    public function test_klaim_orang_lain_atau_yang_sudah_diputuskan_tidak_bisa_diubah(): void
    {
        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->actingAs($this->dina())->put("/klaim/{$klaimRani->id}", ['bukti' => 'Mencoba mengubah klaim orang lain'])->assertForbidden();

        $klaimDinaDitolak = Claim::where('pengklaim', 'Dina')->where('status', 'Ditolak')->first();
        $this->actingAs($this->dina())->delete("/klaim/{$klaimDinaDitolak->id}")->assertForbidden();
    }

    // ---------- Keputusan petugas dan aturan bisnis ----------

    public function test_petugas_memverifikasi_dan_menolak_laporan(): void
    {
        $laptop = $this->barang('Laptop abu-abu');
        $this->actingAs($this->admin())->patch("/petugas/laporan/{$laptop->id}", ['aksi' => 'tolak'])->assertSessionHasErrors('alasan');
        $this->actingAs($this->admin())->patch("/petugas/laporan/{$laptop->id}", ['aksi' => 'setujui'])->assertSessionHas('sukses');
        $this->assertSame('Terverifikasi', $laptop->fresh()->status);
        $this->get('/cari')->assertSee('Laptop abu-abu');
    }

    public function test_satu_barang_hanya_boleh_satu_klaim_disetujui(): void
    {
        $klaimBudi = Claim::where('pengklaim', 'Budi')->first(); // barangnya sudah punya klaim disetujui
        $this->actingAs($this->admin())->patch("/petugas/klaim/{$klaimBudi->id}", ['aksi' => 'setujui'])->assertSessionHasErrors('alasan');
        $this->assertSame('Menunggu', $klaimBudi->fresh()->status);

        $this->actingAs($this->admin())->patch("/petugas/klaim/{$klaimBudi->id}", ['aksi' => 'tolak', 'alasan' => 'Ciri tidak cocok']);
        $this->assertSame('Ditolak', $klaimBudi->fresh()->status);
        $this->assertSame('Ciri tidak cocok', $klaimBudi->fresh()->alasan);
    }

    public function test_menyetujui_klaim_lalu_serah_terima_mengubah_status_barang(): void
    {
        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->actingAs($this->admin())->patch("/petugas/klaim/{$klaimRani->id}", ['aksi' => 'setujui'])->assertSessionHas('sukses');
        $this->assertSame('Disetujui', $klaimRani->fresh()->status);

        $this->actingAs($this->admin())->patch("/petugas/klaim/{$klaimRani->id}/serah-terima")->assertSessionHas('sukses');
        $this->assertSame('Dikembalikan', $klaimRani->report->fresh()->status);
        $this->get('/cari')->assertDontSee('Dompet kulit hitam');
    }
}
