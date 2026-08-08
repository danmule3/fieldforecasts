@props(['items']) {{-- array of ['label' => string, 'url' => string|null] — last item's url is typically null (current page) --}}

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($items)->values()->map(fn ($item, $index) => array_filter([
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => $item['url'] ?? null,
        ]))->all(),
    ];
@endphp

<nav aria-label="Breadcrumb" class="text-sm text-slate-500 dark:text-slate-400 mb-4">
    @foreach ($items as $index => $item)
        @if (! $loop->first) <span class="mx-1">/</span> @endif
        @if (! empty($item['url']) && ! $loop->last)
            <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['label'] }}</a>
        @else
            <span>{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}</script>
