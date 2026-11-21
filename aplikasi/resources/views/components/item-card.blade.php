{{-- ItemCard: kartu barang temuan dari database. State: normal (hover terangkat), sudah diklaim (redup, tombol nonaktif), terpilih. --}}
@props(['item', 'selected' => false])
@php $claimed = $item->sudahDiklaim(); @endphp
<article @class(['card', 'item-card', 'item-card--claimed' => $claimed, 'item-card--selected' => $selected])>
    <x-item-photo :kategori="$item->kategori" />
    <div class="item-card__body">
        <div><x-status-badge :status="$claimed ? 'Sudah diklaim' : 'Dapat diklaim'" /></div>
        <h3 class="item-card__title">{{ $item->nama }}</h3>
        <p class="item-card__meta">
            <span><x-icon name="pin" :size="15" />{{ $item->lokasi }}</span>
            <span><x-icon name="calendar" :size="15" />{{ $item->tanggal->translatedFormat('d M Y') }}</span>
        </p>
    </div>
    <div class="item-card__foot">
        @if ($claimed)
            <x-button :disabled="true" block>Sudah diklaim</x-button>
        @else
            <x-button variant="secondary" :href="route('barang.show', $item)" block>Lihat Detail <x-icon name="arrow-right" :size="16" /></x-button>
        @endif
    </div>
</article>
