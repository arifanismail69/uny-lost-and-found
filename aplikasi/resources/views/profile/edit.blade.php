<x-layout title="Profil" active="profil">
    <div class="form-narrow stack">
        <div class="page-head" style="margin-bottom: 0">
            <h1>Profil</h1>
            <p>Atur nama, email, dan kata sandi akunmu.</p>
        </div>

        <section class="card card--accent" aria-labelledby="judul-info">
            <h2 id="judul-info">Informasi akun</h2>
            @if (session('status') === 'profile-updated')
                <x-alert type="success" role="status">Profil tersimpan.</x-alert>
            @endif
            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf @method('PATCH')
                <x-form-field name="name" label="Nama" required :value="$user->name" autocomplete="name" />
                <x-form-field name="email" label="Email" type="email" required :value="$user->email" autocomplete="username" />
                <p class="caption">Peran: <span class="role-tag">{{ $user->isAdmin() ? 'Petugas' : 'Pengguna' }}</span> (peran hanya dapat diubah oleh pengelola sistem)</p>
                <x-button type="submit">Simpan profil</x-button>
            </form>
        </section>

        <section class="card" aria-labelledby="judul-sandi">
            <h2 id="judul-sandi">Ganti kata sandi</h2>
            @if (session('status') === 'password-updated')
                <x-alert type="success" role="status">Kata sandi diperbarui.</x-alert>
            @endif
            <form method="POST" action="{{ route('password.update') }}" novalidate>
                @csrf @method('PUT')
                <x-form-field name="current_password" id="f-sandi-lama" label="Kata sandi sekarang" type="password" bag="updatePassword" autocomplete="current-password" />
                <x-form-field name="password" id="f-sandi-baru" label="Kata sandi baru" type="password" bag="updatePassword" autocomplete="new-password" hint="Minimal 8 karakter." />
                <x-form-field name="password_confirmation" id="f-sandi-ulang" label="Ulangi kata sandi baru" type="password" bag="updatePassword" autocomplete="new-password" />
                <x-button type="submit">Ganti kata sandi</x-button>
            </form>
        </section>

        <section class="card" aria-labelledby="judul-hapus" style="border-color: var(--color-danger)">
            <h2 id="judul-hapus">Hapus akun</h2>
            <p class="text-muted">Akun dihapus permanen. Laporan dan klaimmu tetap tersimpan tanpa nama pemilik akun.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" novalidate
                onsubmit="return confirm('Hapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                @csrf @method('DELETE')
                <x-form-field name="password" id="f-sandi-hapus" label="Kata sandi untuk konfirmasi" type="password" bag="userDeletion" autocomplete="current-password" />
                <x-button type="submit" variant="danger">Hapus akun</x-button>
            </form>
        </section>
    </div>
</x-layout>
