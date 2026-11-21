<x-layout title="Masuk">
    <div class="auth-card">
        <div class="card card--accent">
            <div class="card-title" style="margin-bottom: var(--space-4)">
                <span class="icon-bubble"><x-icon name="user" /></span>
                <div><h1 style="margin: 0">Masuk</h1><p class="caption" style="margin: 0">Masuk untuk melapor, mengklaim, dan memantau status.</p></div>
            </div>

            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif
            @if ($errors->any())
                <x-alert type="danger" role="alert">Email atau kata sandi salah. Periksa kembali isianmu.</x-alert>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <x-form-field name="email" label="Email" type="email" required autocomplete="username" autofocus />
                <x-form-field name="password" label="Kata sandi" type="password" required autocomplete="current-password" />
                <label style="display: flex; gap: 8px; align-items: center; margin-bottom: var(--space-4)">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px"> Ingat saya
                </label>
                <x-button type="submit" block>Masuk</x-button>
            </form>

            <div class="actions" style="justify-content: space-between">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
                <span class="caption">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></span>
            </div>
        </div>

        <details class="card card--muted" style="margin-top: var(--space-4)">
            <summary><strong>Akun uji (hanya untuk latihan)</strong></summary>
            <p class="caption" style="margin-top: var(--space-2)">Kata sandi semua akun: <code>password</code></p>
            <ul class="caption" style="margin: 0; padding-left: 18px">
                <li>Petugas (admin): <code>admin@lostfound.test</code></li>
                <li>Pengguna: <code>dina@lostfound.test</code>, <code>rani@lostfound.test</code></li>
            </ul>
        </details>
    </div>
</x-layout>
