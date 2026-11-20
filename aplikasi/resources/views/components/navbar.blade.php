@props(['active' => '', 'admin' => false])
@php
    $menu = $admin
        ? [['petugas', 'Laporan dan Klaim', route('petugas.index'), 'list']]
        : [
            ['beranda', 'Beranda', route('beranda'), 'home'],
            ['cari', 'Cari Barang', route('barang.cari'), 'search'],
            ['lapor', 'Lapor', route('laporan.create'), 'plus'],
            ['riwayat', 'Riwayat', route('riwayat'), 'clock'],
        ];
@endphp
<header class="site-header">
    <div class="container">
        <a class="brand" href="{{ $admin ? route('petugas.index') : route('beranda') }}">
            <span class="brand__mark"><x-icon name="search" :size="18" /></span>
            <span>UNY Lost and Found<span class="brand__sub">{{ $admin ? 'Panel Petugas' : 'Universitas Negeri Yogyakarta' }}</span></span>
        </a>
        <nav aria-label="Navigasi utama" style="display: contents">
            <ul class="nav">
                @foreach ($menu as [$key, $label, $url, $icon])
                    <li><a href="{{ $url }}" @if ($key === $active) aria-current="page" @endif><x-icon :name="$icon" :size="18" />{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>
        <span class="user-chip">
            <x-icon :name="$admin ? 'shield' : 'user'" :size="18" />
            @if ($admin)
                Pak Slamet <span class="role-tag">Petugas</span> · <a href="{{ route('beranda') }}">Tampilan pengguna</a>
            @else
                Dina (simulasi) · <a href="{{ route('petugas.index') }}">Tampilan petugas</a>
            @endif
        </span>
    </div>
</header>
