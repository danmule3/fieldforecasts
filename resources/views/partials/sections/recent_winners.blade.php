<section class="py-8 bg-slate-100/60 dark:bg-slate-900/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-xl font-bold">{{ $section->title ?? 'Recent winners' }}</h2>
            <div class="text-sm">
                Overall prediction accuracy:
                <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $accuracy }}%</span>
            </div>
        </div>

        @if ($recentWinners->isEmpty())
            <p class="text-sm text-slate-500 dark:text-slate-400">No settled predictions yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($recentWinners as $prediction)
                    <x-prediction-card :prediction="$prediction" />
                @endforeach
            </div>
        @endif
    </div>
</section>
