<x-layout title="Konfirmasi kata sandi">
    <div class="auth-card">
        <div class="card card--accent">
            <h1>Konfirmasi kata sandi</h1>
            <p class="text-muted">Ini area aman. Masukkan kata sandimu untuk melanjutkan.</p>
            <form method="POST" action="{{ route('password.confirm') }}" novalidate>
                @csrf
                <x-form-field name="password" label="Kata sandi" type="password" required autocomplete="current-password" />
                <x-button type="submit" block>Konfirmasi</x-button>
            </form>
        </div>
    </div>
</x-layout>
