{{-- Button: variant primary | secondary | danger. Dengan href menjadi tautan; disabled menjadi tombol nonaktif. --}}
@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'disabled' => false, 'block' => false])
@php
    $classes = 'btn' . ($variant !== 'primary' ? ' btn--' . $variant : '') . ($block ? ' btn--block' : '');
@endphp
@if ($href && ! $disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" @disabled($disabled) @if ($disabled) aria-disabled="true" @endif {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
