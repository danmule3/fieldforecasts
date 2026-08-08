<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">{{ $section->title ?? "Today's predictions" }}</h2>
            <a href="{{ route('predictions.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>

        @if ($todaysPredictions->isEmpty())
            <p class="text-sm text-slate-500 dark:text-slate-400">No predictions published for today yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($todaysPredictions as $prediction)
                    <x-prediction-card :prediction="$prediction" />
                @endforeach
            </div>
        @endif
    </div>
</section>
