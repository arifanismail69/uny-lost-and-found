@props(['title', 'text' => null])
<div class="empty-state" role="status">
    <h2>{{ $title }}</h2>
    @if ($text)<p class="text-muted">{{ $text }}</p>@endif
    @if (! $slot->isEmpty())<div class="actions" style="justify-content: center">{{ $slot }}</div>@endif
</div>
