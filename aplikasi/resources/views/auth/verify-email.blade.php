<x-layout title="Verifikasi email">
    <div class="auth-card">
        <div class="card card--accent">
            <h1>Verifikasi email</h1>
            <p class="text-muted">Terima kasih sudah mendaftar. Klik tautan yang kami kirim ke emailmu untuk memverifikasi akun.</p>
            @if (session('status') == 'verification-link-sent')
                <x-alert type="success">Tautan verifikasi baru sudah dikirim ke emailmu.</x-alert>
            @endif
            <div class="actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-button type="submit">Kirim ulang email verifikasi</x-button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button type="submit" variant="secondary">Keluar</x-button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
