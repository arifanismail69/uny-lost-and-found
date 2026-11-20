<x-layout title="Hasil Pencarian" active="cari">
    <div class="page-head">
        <h1>Hasil pencarian</h1>
        <p class="text-muted" role="status">{{ count($hasil) }} barang ditemukan</p>
    </div>

    @if ($filter)
        <div class="chips" aria-label="Filter aktif">
            @foreach ($filter as $kunci => $nilai)
                <span class="chip">{{ ['q' => 'Kata kunci', 'kategori' => 'Kategori', 'lokasi' => 'Lokasi', 'sejak' => 'Sejak'][$kunci] }}: {{ $nilai }}</span>
            @endforeach
            <a href="{{ route('barang.cari') }}">Hapus filter</a>
        </div>
    @endif

    @if (count($hasil) === 0)
        <x-empty-state title="Tidak ada barang yang cocok" text="Coba ubah kata kunci atau kurangi filter.">
            <x-button :href="route('barang.cari')">Hapus filter</x-button>
            <x-button variant="secondary" :href="route('beranda')">Ubah pencarian</x-button>
        </x-empty-state>
    @else
        <div class="grid grid--3">
            @foreach ($hasil as $item)
                <x-item-card :item="$item" />
            @endforeach
        </div>
        <p class="section"><a href="{{ route('beranda') }}">Ubah pencarian</a></p>
    @endif
</x-layout>
