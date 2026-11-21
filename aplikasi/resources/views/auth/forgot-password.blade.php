<x-layout title="Lupa kata sandi">
    <div class="auth-card">
        <div class="card card--accent">
            <h1>Lupa kata sandi</h1>
            <p class="text-muted">Masukkan email akunmu. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>
            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif
            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                <x-form-field name="email" label="Email" type="email" required autofocus />
                <x-button type="submit" block>Kirim tautan atur ulang</x-button>
            </form>
            <p class="caption" style="margin-top: var(--space-4)"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
            <p class="caption">Catatan: pengiriman email belum dikonfigurasi di komputer pengembang (tautan tercatat di log Laravel).</p>
        </div>
    </div>
</x-layout>
