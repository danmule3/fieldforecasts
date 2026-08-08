@php $items = $section->content['items'] ?? []; @endphp

@if (!empty($items))
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($section->title)
                <h2 class="text-2xl font-bold text-center mb-2">{{ $section->title }}</h2>
            @endif
            @if ($section->description)
                <p class="text-slate-500 dark:text-slate-400 text-center mb-8 max-w-2xl mx-auto">{{ $section->description }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($items as $item)
                    <div class="text-center">
                        @if (!empty($item['icon']))
                            <div class="text-3xl mb-2">{{ $item['icon'] }}</div>
                        @endif
                        <h3 class="font-semibold mb-1">{{ $item['title'] ?? '' }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $item['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
