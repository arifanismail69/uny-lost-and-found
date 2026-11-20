{{-- FormField: label, kontrol, teks bantuan, dan pesan galat dari validasi Laravel (@error).
     type: text | date | number | email | password | file | select | textarea --}}
@props([
    'name', 'label', 'type' => 'text', 'options' => [], 'placeholder' => null,
    'required' => false, 'hint' => null, 'value' => null, 'rows' => 4, 'error' => null,
])
@php
    $id = 'f-' . $name;
    $current = old($name, $value);
    $errorMessage = $error ?? $errors->first($name);
    $hasError = $errorMessage !== '' && $errorMessage !== null;
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
<div @class(['field', 'field--error' => $hasError])>
    <label class="field__label" for="{{ $id }}">
        {{ $label }} @if ($required)<span class="field__req" aria-hidden="true">*</span>@endif
    </label>

    @if ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" class="field__control"
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @if ($hasError) aria-invalid="true" @endif>
            <option value="">{{ $placeholder ?? 'Pilih' }}</option>
            @foreach ($options as $opt)
                <option value="{{ $opt }}" @selected($current === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
    @elseif ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" class="field__control" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @if ($hasError) aria-invalid="true" @endif>{{ $current }}</textarea>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" class="field__control"
            @if ($type !== 'file') value="{{ $current }}" @endif placeholder="{{ $placeholder }}"
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @if ($hasError) aria-invalid="true" @endif
            {{ $attributes }}>
    @endif

    @if ($hint)
        <span class="field__hint" id="{{ $id }}-hint">{{ $hint }}</span>
    @endif
    @if ($hasError)
        <span class="field__error" id="{{ $id }}-error">{{ $errorMessage }}</span>
    @endif
</div>
