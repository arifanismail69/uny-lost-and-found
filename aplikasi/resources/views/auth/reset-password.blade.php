<x-layout title="Atur ulang kata sandi">
    <div class="auth-card">
        <div class="card card--accent">
            <h1>Atur ulang kata sandi</h1>
            <form method="POST" action="{{ route('password.store') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <x-form-field name="email" label="Email" type="email" required :value="$request->email" autocomplete="username" />
                <x-form-field name="password" label="Kata sandi baru" type="password" required autocomplete="new-password" />
                <x-form-field name="password_confirmation" label="Ulangi kata sandi baru" type="password" required autocomplete="new-password" />
                <x-button type="submit" block>Simpan kata sandi</x-button>
            </form>
        </div>
    </div>
</x-layout>
