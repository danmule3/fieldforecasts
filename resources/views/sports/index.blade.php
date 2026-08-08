<x-layouts.app :title="__('Sports')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-6">Browse by sport</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($sports as $sport)
                <a href="{{ route('sports.show', $sport) }}"
                   class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6 text-center hover:ring-indigo-500 transition">
                    <div class="font-semibold">{{ $sport->name }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $sport->matches_count }} matches</div>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.app>
