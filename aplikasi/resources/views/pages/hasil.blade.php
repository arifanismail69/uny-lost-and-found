<x-layout title="Hasil Pencarian" active="cari">
    <div class="page-head">
        <h1>Hasil pencarian</h1>
        <p role="status"><strong>{{ count($hasil) }}</strong> barang ditemukan. Barang yang masih bisa diklaim ditampilkan lebih dulu.</p>
    </div>

    @if ($filter)
        <div class="chips" aria-label="Filter aktif">
            @foreach ($filter as $kunci => $nilai)
                <span class="chip"><x-icon name="tag" :size="14" />{{ ['q' => 'Kata kunci', 'kategori' => 'Kategori', 'lokasi' => 'Lokasi', 'sejak' => 'Sejak'][$kunci] }}: {{ $nilai }}</span>
            @endforeach
            <a href="{{ route('barang.cari') }}">Hapus filter</a>
        </div>
    @endif

    @if (count($hasil) === 0)
        <x-empty-state title="Tidak ada barang yang cocok" text="Coba ubah kata kunci atau kurangi filter. Kamu juga bisa membuat laporan kehilangan agar petugas ikut mencarikan.">
            <x-button :href="route('barang.cari')">Hapus filter</x-button>
            <x-button variant="secondary" :href="route('laporan.create', ['jenis' => 'Hilang'])">Lapor Kehilangan</x-button>
        </x-empty-state>
    @else
        <div class="grid grid--3">
            @foreach ($hasil as $item)
                <x-item-card :item="$item" />
            @endforeach
        </div>
        <p class="section"><a href="{{ route('beranda') }}"><x-icon name="arrow-left" :size="16" /> Ubah pencarian</a></p>
    @endif
</x-layout>
