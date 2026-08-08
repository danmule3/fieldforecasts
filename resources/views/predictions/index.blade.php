<x-layouts.app :title="__('Predictions')" :description="__('Daily football and sports match predictions with confidence ratings and analysis.')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-bold">Predictions</h1>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Overall accuracy: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $accuracy }}%</span>
            </div>
        </div>
        <p class="text-slate-500 dark:text-slate-400 mb-8">Informational predictions only — Field Forecast does not accept wagers.</p>

        <form method="GET" class="flex flex-wrap gap-3 mb-8">
            <select name="sport_id" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">All sports</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected(request('sport_id') == $sport->id)>{{ $sport->name }}</option>
                @endforeach
            </select>

            <select name="is_premium" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">Free &amp; premium</option>
                <option value="free" @selected(request('is_premium') === 'free')>Free only</option>
                <option value="premium" @selected(request('is_premium') === 'premium')>Premium only</option>
            </select>

            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @forelse ($predictions as $prediction)
                <x-prediction-card :prediction="$prediction" />
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No predictions match your filters yet.</p>
            @endforelse
        </div>

        {{ $predictions->links() }}
    </div>
</x-layouts.app>
