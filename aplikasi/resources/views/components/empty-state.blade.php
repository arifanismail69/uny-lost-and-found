@props(['title', 'text' => null, 'icon' => 'search'])
<div class="empty-state" role="status">
    <div class="icon-bubble"><x-icon :name="$icon" :size="28" /></div>
    <h2>{{ $title }}</h2>
    @if ($text)<p class="text-muted">{{ $text }}</p>@endif
    @if (! $slot->isEmpty())<div class="actions" style="justify-content: center">{{ $slot }}</div>@endif
</div>
