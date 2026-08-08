<x-layouts.app :title="__('Matches')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-6">All matches</h1>

        <form method="GET" class="flex flex-wrap gap-3 mb-8">
            <select name="sport" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">All sports</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->slug }}" @selected(($filters['sport'] ?? null) === $sport->slug)>{{ $sport->name }}</option>
                @endforeach
            </select>

            <select name="status" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">Any status</option>
                @foreach (['scheduled', 'live', 'finished', 'postponed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? null) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}"
                   class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">

            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @forelse ($matches as $match)
                <x-match-card :match="$match" />
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No matches match your filters.</p>
            @endforelse
        </div>

        {{ $matches->links() }}
    </div>
</x-layouts.app>
