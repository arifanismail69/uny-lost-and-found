<?php

namespace Tests\Feature;

use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Uji antarmuka Pertemuan 4, kini membaca data dari database hasil seeder. */
class AntarmukaPertemuan4Test extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function barang(string $nama): Report
    {
        return Report::where('nama', $nama)->where('jenis', 'Temuan')->firstOrFail();
    }

    public function test_semua_halaman_utama_dapat_dibuka(): void
    {
        foreach (['/', '/cari', '/barang/' . $this->barang('Dompet kulit hitam')->id, '/laporan/create', '/laporan', '/riwayat', '/petugas', '/komponen'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_pencarian_menyaring_kategori_dan_menampilkan_state_kosong(): void
    {
        $this->get('/cari?kategori=Dompet')->assertSee('Dompet kulit hitam')->assertDontSee('Botol minum biru');
        $this->get('/cari?q=tidakada')->assertSee('Tidak ada barang yang cocok');
    }

    public function test_barang_menunggu_atau_dikembalikan_tidak_tampil_publik(): void
    {
        $this->get('/cari')->assertDontSee('Laptop abu-abu')->assertDontSee('Tas ransel biru');
        $this->get('/barang/' . $this->barang('Laptop abu-abu')->id)->assertNotFound();
        $this->get('/barang/' . $this->barang('Tas ransel biru')->id)->assertNotFound();
        $this->get('/barang/9999')->assertNotFound();
    }

    public function test_detail_tidak_menampilkan_ciri_rahasia(): void
    {
        $this->get('/barang/' . $this->barang('Dompet kulit hitam')->id)
            ->assertSee('Warna hitam')->assertDontSee('Foto keluarga di slot kartu');
    }

    public function test_barang_yang_sudah_diklaim_menonaktifkan_klaim(): void
    {
        $this->get('/barang/' . $this->barang('Dompet hitam polos')->id)
            ->assertSee('Barang sudah diklaim')->assertDontSee('Kirim Klaim');
    }
}
