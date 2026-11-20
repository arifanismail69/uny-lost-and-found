<x-layout title="Cari Barang" active="beranda">
    <x-slot:hero>
        <section class="hero">
            <div class="container">
                <span class="hero__eyebrow"><x-icon name="shield" :size="16" /> Layanan resmi kampus · diverifikasi petugas</span>
                <h1>Kehilangan barang di kampus? Kita bantu temukan.</h1>
                <p>Cari barang temuan di seluruh lingkungan UNY, ajukan klaim dengan ciri pembuktian, dan pantau statusnya sampai barang kembali.</p>
            </div>
        </section>
    </x-slot:hero>

    <form class="card hero-search card--accent" method="GET" action="{{ route('barang.cari') }}" aria-label="Form pencarian barang">
        <div class="search-bar">
            <x-form-field name="q" label="Kata kunci" placeholder="Contoh: dompet hitam" />
            <x-form-field name="kategori" label="Kategori" type="select" :options="$kategori" placeholder="Semua kategori" />
            <x-form-field name="lokasi" label="Lokasi" type="select" :options="$lokasi" placeholder="Semua lokasi" />
            <x-form-field name="sejak" label="Sejak tanggal" type="date" />
            <x-button type="submit"><x-icon name="search" :size="18" /> Cari Barang</x-button>
        </div>
    </form>

    <div class="grid grid--2 section" style="margin-top: var(--space-5)">
        <div class="card">
            <div class="card-title" style="margin-bottom: var(--space-3)">
                <span class="icon-bubble"><x-icon name="search" /></span>
                <h2 style="margin: 0">Kehilangan barang?</h2>
            </div>
            <p class="text-muted">Buat laporan agar petugas dan penemu dapat mencocokkannya.</p>
            <x-button variant="secondary" :href="route('laporan.create', ['jenis' => 'Hilang'])">Lapor Kehilangan</x-button>
        </div>
        <div class="card">
            <div class="card-title" style="margin-bottom: var(--space-3)">
                <span class="icon-bubble icon-bubble--accent"><x-icon name="box" /></span>
                <h2 style="margin: 0">Menemukan barang?</h2>
            </div>
            <p class="text-muted">Laporkan temuan agar pemiliknya dapat mengklaim.</p>
            <x-button variant="accent" :href="route('laporan.create', ['jenis' => 'Temuan'])">Lapor Temuan</x-button>
        </div>
    </div>

    <section class="section">
        <div class="section-head">
            <h2>Barang temuan terbaru</h2>
            <a href="{{ route('barang.cari') }}">Lihat semua barang <x-icon name="arrow-right" :size="16" /></a>
        </div>
        <div class="grid grid--4">
            @foreach ($terbaru as $item)
                <x-item-card :item="$item" />
            @endforeach
        </div>
    </section>

    <section class="section" aria-labelledby="cara-kerja">
        <div class="section-head"><h2 id="cara-kerja">Cara barang kembali ke pemiliknya</h2></div>
        <ol class="steps">
            <li><h3>Cari barangmu</h3><p>Saring berdasarkan kategori, lokasi, dan tanggal barang ditemukan.</p></li>
            <li><h3>Ajukan klaim</h3><p>Sebutkan ciri yang hanya diketahui pemilik. Ciri rahasia hanya dilihat petugas.</p></li>
            <li><h3>Ambil di pos petugas</h3><p>Setelah klaim disetujui, lokasi pengambilan muncul di Riwayat.</p></li>
        </ol>
    </section>
</x-layout>
