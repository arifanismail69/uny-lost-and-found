<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Uji matriks hak akses Pertemuan 6: tamu, pengguna, dan admin (petugas). */
class AksesPertemuan6Test extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function akun(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    // ---------- Tamu ----------

    public function test_tamu_boleh_melihat_barang_tetapi_area_lain_diarahkan_ke_login(): void
    {
        $this->get('/')->assertOk();
        $this->get('/cari')->assertOk();

        foreach (['/dashboard', '/riwayat', '/laporan/create', '/laporan', '/petugas', '/profile'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $dompet = Report::where('nama', 'Dompet cokelat')->first();
        $this->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Klaim dari tamu tanpa login'])->assertRedirect('/login');
        $this->assertSame(0, Claim::where('report_id', $dompet->id)->count());
    }

    public function test_tamu_melihat_ajakan_masuk_di_detail_barang(): void
    {
        $dompet = Report::where('nama', 'Dompet cokelat')->first();
        $this->get("/barang/{$dompet->id}")->assertSee('Masuk untuk mengajukan klaim')->assertDontSee('Kirim Klaim');
    }

    // ---------- Registrasi dan login ----------

    public function test_akun_baru_otomatis_berperan_pengguna_dan_tidak_bisa_menyisipkan_role(): void
    {
        $this->post('/register', [
            'name' => 'Mahasiswa Baru', 'email' => 'baru@lostfound.test',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123', 'role' => 'admin',
        ])->assertRedirect('/dashboard');

        $this->assertSame(User::ROLE_PENGGUNA, User::where('email', 'baru@lostfound.test')->value('role'));
    }

    public function test_login_dengan_password_salah_ditolak(): void
    {
        $this->post('/login', ['email' => 'dina@lostfound.test', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_lalu_dashboard_kembali_ke_login(): void
    {
        $this->actingAs($this->akun('dina@lostfound.test'))->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }

    // ---------- Pengguna (peminjam) ----------

    public function test_pengguna_ditolak_di_area_petugas(): void
    {
        $dina = $this->akun('dina@lostfound.test');
        $this->actingAs($dina)->get('/petugas')->assertForbidden();
        $this->actingAs($dina)->get('/laporan')->assertForbidden();

        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->actingAs($dina)->patch("/petugas/klaim/{$klaimRani->id}", ['aksi' => 'setujui'])->assertForbidden();
        $this->assertSame('Menunggu', $klaimRani->fresh()->status);
    }

    public function test_pengguna_hanya_melihat_riwayat_milik_sendiri(): void
    {
        $this->actingAs($this->akun('rani@lostfound.test'))->get('/riwayat')
            ->assertSee('Dompet kulit hitam')      // klaim milik Rani
            ->assertDontSee('Payung lipat hijau'); // klaim milik Dina
    }

    public function test_pengguna_tidak_bisa_membuka_edit_milik_orang_lain(): void
    {
        $klaimRani = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->actingAs($this->akun('dina@lostfound.test'))->get("/klaim/{$klaimRani->id}/edit")->assertForbidden();

        $laptopRaka = Report::where('nama', 'Laptop abu-abu')->first(); // milik Raka, masih menunggu
        $this->actingAs($this->akun('dina@lostfound.test'))->get("/laporan/{$laptopRaka->id}/edit")->assertForbidden();
        $this->actingAs($this->akun('raka@lostfound.test'))->get("/laporan/{$laptopRaka->id}/edit")->assertOk();
    }

    public function test_klaim_bisa_diubah_saat_menunggu_tetapi_tidak_setelah_disetujui(): void
    {
        $rani = $this->akun('rani@lostfound.test');
        $klaim = Claim::where('pengklaim', 'Rani')->where('status', 'Menunggu')->first();
        $this->actingAs($rani)->put("/klaim/{$klaim->id}", ['bukti' => 'Foto keluarga dan dua lembar lima puluh ribu'])->assertRedirect('/riwayat');

        $this->actingAs($this->akun('admin@lostfound.test'))->patch("/petugas/klaim/{$klaim->id}", ['aksi' => 'setujui']);
        $this->actingAs($rani)->put("/klaim/{$klaim->id}", ['bukti' => 'Mengubah setelah disetujui'])->assertForbidden();
    }

    // ---------- Admin (petugas) ----------

    public function test_admin_masuk_area_petugas_dan_melihat_semua_data(): void
    {
        $admin = $this->akun('admin@lostfound.test');
        $this->actingAs($admin)->get('/petugas?status=semua')->assertOk()->assertSee('KL-001')->assertSee('KL-005');
        $this->actingAs($admin)->get('/laporan')->assertOk()->assertSee('Laptop abu-abu');
    }

    public function test_admin_tidak_dapat_mengajukan_klaim(): void
    {
        $dompet = Report::where('nama', 'Dompet cokelat')->first();
        $this->actingAs($this->akun('admin@lostfound.test'))
            ->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Petugas mencoba mengklaim'])->assertForbidden();
    }

    public function test_klaim_ditolak_membebaskan_barang_untuk_klaim_baru(): void
    {
        $admin = $this->akun('admin@lostfound.test');
        $dompet = Report::where('nama', 'Dompet cokelat')->first();
        $this->actingAs($this->akun('dina@lostfound.test'))->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Klaim pertama Dina untuk dompet']);
        $klaim = Claim::latest('id')->first();

        $this->actingAs($admin)->patch("/petugas/klaim/{$klaim->id}", ['aksi' => 'tolak', 'alasan' => 'Ciri kurang spesifik']);
        $this->actingAs($this->akun('dina@lostfound.test'))
            ->post("/barang/{$dompet->id}/klaim", ['bukti' => 'Klaim kedua dengan ciri lebih lengkap'])->assertRedirect('/riwayat');
        $this->assertSame(2, Claim::where('report_id', $dompet->id)->count());
    }
}
