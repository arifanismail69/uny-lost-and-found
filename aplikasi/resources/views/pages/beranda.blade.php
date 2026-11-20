<x-layout title="Cari Barang" active="beranda">
    <div class="page-head">
        <h1>Cari barang yang hilang atau ditemukan</h1>
        <p class="text-muted">Hanya barang yang sudah diverifikasi petugas yang ditampilkan.</p>
    </div>

    <form class="card" method="GET" action="{{ route('barang.cari') }}" aria-label="Form pencarian barang">
        <div class="search-bar">
            <x-form-field name="q" label="Kata kunci" placeholder="Contoh: dompet hitam" />
            <x-form-field name="kategori" label="Kategori" type="select" :options="$kategori" placeholder="Semua kategori" />
            <x-form-field name="lokasi" label="Lokasi" type="select" :options="$lokasi" placeholder="Semua lokasi" />
            <x-form-field name="sejak" label="Sejak tanggal" type="date" />
            <div class="field"><x-button type="submit">Cari Barang</x-button></div>
        </div>
    </form>

    <div class="grid grid--2 section">
        <div class="card">
            <h2>Kehilangan barang?</h2>
            <p class="text-muted">Buat laporan agar petugas dan penemu dapat mencocokkannya.</p>
            <x-button variant="secondary" :href="route('laporan.create', ['jenis' => 'Hilang'])">Lapor Kehilangan</x-button>
        </div>
        <div class="card">
            <h2>Menemukan barang?</h2>
            <p class="text-muted">Laporkan temuan agar pemiliknya dapat mengklaim.</p>
            <x-button variant="secondary" :href="route('laporan.create', ['jenis' => 'Temuan'])">Lapor Temuan</x-button>
        </div>
    </div>

    <section class="section">
        <h2>Barang temuan terbaru</h2>
        <div class="grid grid--4">
            @foreach ($terbaru as $item)
                <x-item-card :item="$item" />
            @endforeach
        </div>
    </section>
</x-layout>
