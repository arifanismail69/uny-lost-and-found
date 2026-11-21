<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Uji antarmuka Pertemuan 4, kini dengan data database dan akun login. */
class AntarmukaPertemuan4Test extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function barang(string $nama): Report
    {
        return Report::where('nama', $nama)->where('jenis', 'Temuan')->firstOrFail();
    }

    private function akun(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function test_halaman_publik_dapat_dibuka_tamu(): void
    {
        foreach (['/', '/cari', '/barang/' . $this->barang('Dompet kulit hitam')->id, '/komponen', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_halaman_pengguna_dan_petugas_dapat_dibuka_sesuai_peran(): void
    {
        $dina = $this->akun('dina@lostfound.test');
        foreach (['/dashboard', '/laporan/create', '/riwayat', '/profile'] as $url) {
            $this->actingAs($dina)->get($url)->assertOk();
        }

        $admin = $this->akun('admin@lostfound.test');
        foreach (['/dashboard', '/laporan', '/petugas', '/profile'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
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
        $this->actingAs($this->akun('dina@lostfound.test'))
            ->get('/barang/' . $this->barang('Dompet hitam polos')->id)
            ->assertSee('Barang sudah diklaim')->assertDontSee('Kirim Klaim');
    }
}
