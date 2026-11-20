{{-- ItemCard: kartu barang temuan. State: normal, sudah diklaim (nonaktif), terpilih. --}}
@props(['item', 'selected' => false])
@php $claimed = \App\Support\DemoData::sudahDiklaim($item['kode']); @endphp
<article @class(['card', 'item-card', 'item-card--claimed' => $claimed, 'item-card--selected' => $selected])>
    <div class="photo" aria-hidden="true">Foto barang</div>
    <div>
        <h3 class="item-card__title">{{ $item['nama'] }}</h3>
        <p class="item-card__meta">{{ $item['lokasi'] }} · {{ \Illuminate\Support\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') }}</p>
    </div>
    <div><x-status-badge :status="$claimed ? 'Sudah diklaim' : 'Dapat diklaim'" /></div>
    <div class="item-card__foot">
        @if ($claimed)
            <x-button :disabled="true" block>Sudah diklaim</x-button>
        @else
            <x-button variant="secondary" :href="route('barang.show', $item['kode'])" block>Lihat Detail</x-button>
        @endif
    </div>
</article>
