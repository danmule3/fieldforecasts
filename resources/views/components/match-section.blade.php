@props(['title', 'matches', 'emptyText' => 'No matches to show right now.'])

<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold mb-4">{{ $title }}</h2>

        @if ($matches->isEmpty())
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyText }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($matches as $match)
                    <x-match-card :match="$match" />
                @endforeach
            </div>
        @endif
    </div>
</section>
