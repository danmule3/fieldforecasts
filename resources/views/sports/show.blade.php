<x-layouts.app :title="$sport->name" :description="$sport->name . ' match predictions, odds and statistics.'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-2">{{ $sport->name }}</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">Predictions, odds, and statistics for {{ $sport->name }}.</p>

        <div class="flex flex-wrap gap-2 mb-10">
            @foreach ($leagues as $league)
                <a href="{{ route('leagues.show', $league) }}"
                   class="rounded-full px-4 py-1.5 text-sm font-medium bg-white dark:bg-slate-900 ring-1 ring-slate-900/5 dark:ring-white/10 hover:ring-indigo-500">
                    {{ $league->name }}
                </a>
            @endforeach
        </div>
    </div>

    <x-match-section title="Live now" :matches="$sections['live']" empty-text="No live {{ $sport->name }} matches right now." />
    <x-match-section title="Today" :matches="$sections['today']" empty-text="No {{ $sport->name }} matches scheduled today." />
    <x-match-section title="Upcoming" :matches="$sections['upcoming']" empty-text="No upcoming {{ $sport->name }} matches." />
</x-layouts.app>
