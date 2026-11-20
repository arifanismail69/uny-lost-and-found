@props(['title' => 'UNY Lost and Found', 'active' => '', 'admin' => false])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - UNY Lost and Found</title>
    <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <x-navbar :active="$active" :admin="$admin" />

    <main class="main" id="konten">
        <div class="container">
            @if (session('sukses'))
                <x-alert type="success" role="status">{{ session('sukses') }}</x-alert>
            @endif
            {{ $slot }}
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            UNY Lost and Found - Praktik Aplikasi Web (INF60295), Teknologi Informasi S1, UNY.
            Data pada tampilan ini masih simulasi (Pertemuan 4).
        </div>
    </footer>
</body>
</html>
