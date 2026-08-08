<x-layouts.admin :title="'Odds'">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h2>
        <a href="{{ route('admin.matches.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">&larr; Back to matches</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h3 class="font-semibold mb-4">Current odds</h3>
            @forelse ($oddsByMarket as $marketName => $odds)
                <div class="mb-4">
                    <p class="text-sm font-medium mb-1">{{ $marketName }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($odds as $odd)
                            <span class="text-xs rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">{{ $odd->selection }}: {{ $odd->price }}</span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No odds entered yet.</p>
            @endforelse
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h3 class="font-semibold mb-4">Add / update odds</h3>
            <form method="POST" action="{{ route('admin.odds.store', $match) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Market</label>
                    <select name="market_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                        @foreach ($markets as $market)
                            <option value="{{ $market->id }}">{{ $market->name }}</option>
                        @endforeach
                    </select>
                </div>

                @foreach (range(0, 2) as $i)
                    <div class="grid grid-cols-2 gap-3">
                        <x-input label="Selection {{ $i + 1 }}" name="selections[{{ $i }}][selection]" placeholder="e.g. Home" />
                        <x-input label="Price" name="selections[{{ $i }}][price]" type="number" step="0.01" min="1" />
                    </div>
                @endforeach

                <p class="text-xs text-slate-400">Leave a row blank to skip it. Odds are informational only — Field Forecast does not accept wagers.</p>

                <x-button type="submit">Save odds</x-button>
            </form>
        </div>
    </div>
</x-layouts.admin>
