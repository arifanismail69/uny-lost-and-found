@props(['title' => 'UNY Lost and Found', 'active' => '', 'admin' => false])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - UNY Lost and Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <x-navbar :active="$active" :admin="$admin" />

    {{ $hero ?? '' }}

    <main @class(['main', 'main--flush' => isset($hero)]) id="konten">
        <div class="container">
            @if (session('sukses'))
                <x-alert type="success" role="status">{{ session('sukses') }}</x-alert>
            @endif
            {{ $slot }}
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <strong>UNY Lost and Found</strong> · Layanan barang hilang dan ditemukan di lingkungan Universitas Negeri Yogyakarta.
            Praktik Aplikasi Web (INF60295). Data pada tampilan ini masih simulasi.
        </div>
    </footer>
</body>
</html>
