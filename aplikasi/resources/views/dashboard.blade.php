<x-layout title="Dashboard" active="dashboard">
    @php $user = auth()->user(); @endphp
    <div class="page-head">
        <h1>Halo, {{ $user->name }}</h1>
        <p>Masuk sebagai <span class="role-tag">{{ $user->isAdmin() ? 'Petugas' : 'Pengguna' }}</span> · {{ $user->email }}</p>
    </div>

    <div class="grid grid--4">
        @foreach ($ringkasan as [$label, $angka, $ikon, $url])
            <a href="{{ $url }}" class="card stat-card">
                <span class="icon-bubble"><x-icon :name="$ikon" /></span>
                <span class="stat-card__angka">{{ $angka }}</span>
                <span class="caption">{{ $label }}</span>
            </a>
        @endforeach
    </div>

    <section class="section">
        <div class="section-head"><h2>Langkah cepat</h2></div>
        <div class="actions" style="margin-top: 0">
            @if ($user->isAdmin())
                <x-button :href="route('petugas.index')"><x-icon name="list" :size="18" /> Tinjau laporan dan klaim</x-button>
                <x-button variant="secondary" :href="route('laporan.index')">Kelola laporan</x-button>
            @else
                <x-button :href="route('barang.cari')"><x-icon name="search" :size="18" /> Cari barang</x-button>
                <x-button variant="accent" :href="route('laporan.create', ['jenis' => 'Temuan'])">Lapor temuan</x-button>
                <x-button variant="secondary" :href="route('laporan.create', ['jenis' => 'Hilang'])">Lapor kehilangan</x-button>
                <x-button variant="secondary" :href="route('riwayat')">Lihat riwayat</x-button>
            @endif
        </div>
    </section>
</x-layout>
