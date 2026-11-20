{{-- StatusBadge: warna selalu disertai teks status. --}}
@props(['status'])
@php
    $variant = match ($status) {
        'Terverifikasi', 'Disetujui', 'Dikembalikan', 'Dapat diklaim' => 'success',
        'Menunggu', 'Menunggu verifikasi' => 'warning',
        'Ditolak' => 'danger',
        default => 'neutral',
    };
@endphp
<span {{ $attributes->merge(['class' => 'badge badge--' . $variant]) }}>{{ $status }}</span>
