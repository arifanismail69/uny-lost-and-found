<?php

namespace Tests\Feature;

use Tests\TestCase;

/** Uji antarmuka Pertemuan 4 (data masih simulasi). */
class AntarmukaPertemuan4Test extends TestCase
{
    public function test_semua_halaman_utama_dapat_dibuka(): void
    {
        foreach (['/', '/cari', '/barang/LP-001', '/lapor', '/riwayat', '/petugas', '/komponen'] as $url) {
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
        $this->get('/barang/LP-008')->assertNotFound();
        $this->get('/barang/LP-007')->assertNotFound();
    }

    public function test_detail_tidak_menampilkan_ciri_rahasia(): void
    {
        $this->get('/barang/LP-001')->assertSee('Warna hitam')->assertDontSee('Foto keluarga di slot kartu');
    }

    public function test_barang_yang_sudah_diklaim_menonaktifkan_klaim(): void
    {
        $this->get('/barang/LP-006')->assertSee('Barang sudah diklaim')->assertDontSee('Kirim Klaim');
    }

    public function test_klaim_wajib_ciri_pembuktian(): void
    {
        $this->from('/barang/LP-001')->post('/barang/LP-001/klaim', ['bukti' => ''])
            ->assertRedirect('/barang/LP-001')->assertSessionHasErrors('bukti');

        $this->post('/barang/LP-001/klaim', ['bukti' => 'Ada foto keluarga di slot kartu'])
            ->assertRedirect('/riwayat')->assertSessionHas('sukses');
    }

    public function test_lapor_temuan_memvalidasi_kolom_wajib_dan_tanggal(): void
    {
        $this->post('/lapor', ['jenis' => 'Temuan', 'tanggal' => now()->addDay()->toDateString()])
            ->assertSessionHasErrors(['nama', 'kategori', 'ciri', 'lokasi', 'tanggal', 'dititipkan_di']);

        $this->post('/lapor', [
            'jenis' => 'Hilang', 'nama' => 'Jam tangan', 'kategori' => 'Lainnya', 'ciri' => 'Hitam',
            'lokasi' => 'Kantin FT', 'tanggal' => now()->toDateString(),
        ])->assertRedirect('/riwayat')->assertSessionHasNoErrors();
    }

    public function test_petugas_menolak_wajib_alasan_dan_klaim_ganda_tidak_bisa_disetujui(): void
    {
        $this->post('/petugas/KL-002/keputusan', ['aksi' => 'tolak'])->assertSessionHasErrors('alasan');
        $this->post('/petugas/KL-002/keputusan', ['aksi' => 'tolak', 'alasan' => 'Ciri tidak cocok'])->assertSessionHas('sukses');
        $this->post('/petugas/KL-005/keputusan', ['aksi' => 'setujui'])->assertSessionHasErrors('alasan');
    }
}
