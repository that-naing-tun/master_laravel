@if (!isset($show) || $show)
    <span class="btn btn-{{ $type ?? 'success' }}">{{ $slot }}</span>
@endif
