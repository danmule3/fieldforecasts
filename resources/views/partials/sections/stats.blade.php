@php $items = $section->content['items'] ?? []; @endphp

@if (!empty($items))
    <section class="py-6 bg-indigo-600 dark:bg-indigo-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center text-white">
                @foreach ($items as $item)
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">{{ $item['value'] ?? '' }}</p>
                        <p class="text-sm text-indigo-100">{{ $item['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
