{{-- Navigasi menyesuaikan status login dan peran. Menyembunyikan menu hanya untuk kenyamanan;
     pengamanan sebenarnya ada di middleware route dan policy. --}}
@props(['active' => ''])
@php
    $user = auth()->user();
    $admin = $user?->isAdmin() ?? false;
    $menu = match (true) {
        $admin => [
            ['dashboard', 'Dashboard', route('dashboard'), 'home'],
            ['petugas', 'Laporan dan Klaim', route('petugas.index'), 'list'],
            ['kelola', 'Kelola Laporan', route('laporan.index'), 'box'],
            ['cari', 'Cari Barang', route('barang.cari'), 'search'],
        ],
        (bool) $user => [
            ['beranda', 'Beranda', route('beranda'), 'home'],
            ['cari', 'Cari Barang', route('barang.cari'), 'search'],
            ['lapor', 'Lapor', route('laporan.create'), 'plus'],
            ['riwayat', 'Riwayat', route('riwayat'), 'clock'],
        ],
        default => [
            ['beranda', 'Beranda', route('beranda'), 'home'],
            ['cari', 'Cari Barang', route('barang.cari'), 'search'],
        ],
    };
@endphp
<header class="site-header">
    <div class="container">
        <a class="brand" href="{{ $admin ? route('dashboard') : route('beranda') }}">
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
        <div class="user-chip">
            @auth
                <a href="{{ route('profile.edit') }}" class="user-chip__nama" @if ($active === 'profil') aria-current="page" @endif>
                    <x-icon :name="$admin ? 'shield' : 'user'" :size="18" /> {{ $user->name }}
                    @if ($admin)<span class="role-tag">Petugas</span>@endif
                </a>
                @unless ($admin)
                    <a href="{{ route('dashboard') }}" @if ($active === 'dashboard') aria-current="page" @endif>Dashboard</a>
                @endunless
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="link-button">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn--accent btn--small">Daftar</a>
            @endauth
        </div>
    </div>
</header>
