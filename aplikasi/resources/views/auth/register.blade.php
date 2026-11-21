<x-layout title="Daftar">
    <div class="auth-card">
        <div class="card card--accent">
            <div class="card-title" style="margin-bottom: var(--space-4)">
                <span class="icon-bubble icon-bubble--accent"><x-icon name="plus" /></span>
                <div><h1 style="margin: 0">Daftar akun</h1><p class="caption" style="margin: 0">Akun baru terdaftar sebagai pengguna (pelapor/pengklaim).</p></div>
            </div>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <x-form-field name="name" label="Nama" required autocomplete="name" autofocus />
                <x-form-field name="email" label="Email" type="email" required autocomplete="username" hint="Disarankan email kampus." />
                <x-form-field name="password" label="Kata sandi" type="password" required autocomplete="new-password" hint="Minimal 8 karakter." />
                <x-form-field name="password_confirmation" label="Ulangi kata sandi" type="password" required autocomplete="new-password" />
                <x-button type="submit" block>Daftar</x-button>
            </form>

            <p class="caption" style="margin-top: var(--space-4)">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>
</x-layout>
