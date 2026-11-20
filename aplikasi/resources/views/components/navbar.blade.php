@props(['active' => '', 'admin' => false])
@php
    $menu = $admin
        ? [['petugas', 'Laporan dan Klaim', route('petugas.index')]]
        : [
            ['beranda', 'Beranda', route('beranda')],
            ['cari', 'Cari Barang', route('barang.cari')],
            ['lapor', 'Lapor', route('laporan.create')],
            ['riwayat', 'Riwayat', route('riwayat')],
        ];
@endphp
<header @class(['site-header', 'site-header--admin' => $admin])>
    <div class="container">
        <a class="brand" href="{{ $admin ? route('petugas.index') : route('beranda') }}">UNY Lost and Found</a>
        <nav aria-label="Navigasi utama" style="display: contents">
            <ul class="nav">
                @foreach ($menu as [$key, $label, $url])
                    <li><a href="{{ $url }}" @if ($key === $active) aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>
        <span class="user-chip">
            @if ($admin)
                Pak Slamet (Petugas) · <a href="{{ route('beranda') }}" style="color: inherit">Tampilan pengguna</a>
            @else
                Dina (simulasi) · <a href="{{ route('petugas.index') }}">Tampilan petugas</a>
            @endif
        </span>
    </div>
</header>
