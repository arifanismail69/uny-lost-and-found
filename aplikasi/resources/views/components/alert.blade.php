@props(['type' => 'success'])
@php $ikon = ['success' => 'check', 'warning' => 'lock', 'danger' => 'alert'][$type] ?? 'check'; @endphp
<div {{ $attributes->merge(['class' => 'alert alert--' . $type]) }}>
    <x-icon :name="$ikon" :size="20" />
    <div>{{ $slot }}</div>
</div>
