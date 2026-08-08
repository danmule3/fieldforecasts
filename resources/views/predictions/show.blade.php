<x-layouts.app
    :title="$prediction->match->homeTeam->name . ' vs ' . $prediction->match->awayTeam->name . ' prediction'"
    :description="'Prediction, confidence rating and analysis for ' . $prediction->match->homeTeam->name . ' vs ' . $prediction->match->awayTeam->name . '.'"
>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="[
            ['label' => 'Predictions', 'url' => route('predictions.index')],
            ['label' => $prediction->match->homeTeam->name . ' vs ' . $prediction->match->awayTeam->name, 'url' => route('matches.show', $prediction->match)],
            ['label' => $prediction->market->name, 'url' => null],
        ]" />

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">{{ $prediction->market->name }} prediction</h1>
            @auth
                <form method="POST" action="{{ route('predictions.save', $prediction) }}">
                    @csrf
                    <x-button variant="{{ $isSaved ? 'secondary' : 'primary' }}">
                        {{ $isSaved ? '★ Saved' : '☆ Save prediction' }}
                    </x-button>
                </form>
            @endauth
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Our pick</p>
                    <p class="text-xl font-bold">{{ $prediction->pick }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Confidence</p>
                    <p class="text-xl font-bold">{{ $prediction->confidence }}%</p>
                </div>
                @if ($prediction->odds_at_publish)
                    <div class="text-right">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Odds</p>
                        <p class="text-xl font-bold">{{ $prediction->odds_at_publish }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if ($canViewFull)
            <div class="space-y-6">
                <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                    <h2 class="font-semibold mb-2">Analysis</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $prediction->analysis }}</p>
                </section>

                @if ($prediction->reasoning)
                    <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                        <h2 class="font-semibold mb-2">Reasoning</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $prediction->reasoning }}</p>
                    </section>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if ($prediction->recent_form_summary)
                        <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                            <h2 class="font-semibold mb-2">Recent form</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $prediction->recent_form_summary }}</p>
                        </section>
                    @endif

                    @if ($prediction->head_to_head_summary)
                        <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                            <h2 class="font-semibold mb-2">Head to head</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $prediction->head_to_head_summary }}</p>
                        </section>
                    @endif
                </div>

                @if ($prediction->injury_notes)
                    <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                        <h2 class="font-semibold mb-2">Injuries</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $prediction->injury_notes }}</p>
                    </section>
                @endif
            </div>
        @else
            <div class="rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-10 text-center bg-white dark:bg-slate-900">
                <p class="font-semibold mb-2">This is a Premium prediction</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    Subscribe to unlock full analysis, reasoning, form, and head-to-head statistics.
                </p>
                <a href="{{ route('dashboard') }}"><x-button>View subscription options</x-button></a>
            </div>
        @endif

        <p class="mt-8 text-xs text-slate-400 text-center">
            Informational prediction only — Field Forecast does not facilitate betting or accept wagers.
        </p>
    </div>
</x-layouts.app>
