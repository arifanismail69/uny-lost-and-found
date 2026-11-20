{{-- ItemPhoto: ilustrasi per kategori sebagai pengganti foto barang (foto asli diunggah mulai Pertemuan 5). --}}
@props(['kategori', 'large' => false])
@php
    $ikon = ['Dompet' => 'wallet', 'Tas' => 'bag', 'Botol' => 'bottle', 'Kunci' => 'key', 'Payung' => 'umbrella', 'Elektronik' => 'laptop'][$kategori] ?? 'box';
@endphp
<div @class(['item-photo', 'item-photo--' . $kategori, 'item-photo--large' => $large]) role="img" aria-label="Ilustrasi kategori {{ $kategori }}">
    <span class="item-photo__tag">{{ $kategori }}</span>
    <x-icon :name="$ikon" :size="$large ? 96 : 56" />
</div>
