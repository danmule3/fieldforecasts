<x-layouts.app
    :title="$match->homeTeam->name . ' vs ' . $match->awayTeam->name"
    :description="'Prediction, odds and analysis for ' . $match->homeTeam->name . ' vs ' . $match->awayTeam->name . ' — ' . $match->league->name . '.'"
>
    <x-slot:schema>
        <x-schema.sports-event :match="$match" />
    </x-slot:schema>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="[
            ['label' => $match->sport->name, 'url' => route('sports.show', $match->sport)],
            ['label' => $match->league->name, 'url' => route('leagues.show', $match->league)],
            ['label' => $match->homeTeam->name . ' vs ' . $match->awayTeam->name, 'url' => null],
        ]" />

        <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-8 text-center">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                {{ $match->kickoff_at->format('l, d M Y - H:i') }}
                @if ($match->venue) &middot; {{ $match->venue }} @endif
            </p>

            <div class="flex items-center justify-center gap-8 text-lg font-bold">
                <a href="{{ route('teams.show', $match->homeTeam) }}" class="hover:underline">{{ $match->homeTeam->name }}</a>
                @if ($match->status === \App\Models\GameMatch::STATUS_FINISHED || $match->isLive())
                    <span class="text-2xl">{{ $match->home_score }} - {{ $match->away_score }}</span>
                @else
                    <span class="text-slate-400 font-normal">vs</span>
                @endif
                <a href="{{ route('teams.show', $match->awayTeam) }}" class="hover:underline">{{ $match->awayTeam->name }}</a>
            </div>

            @if ($match->isLive())
                <p class="mt-4 text-red-600 dark:text-red-400 text-sm font-semibold">LIVE @if($match->minute) — {{ $match->minute }}' @endif</p>
            @endif
        </div>

        @if ($match->statistics)
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-3">Match statistics</h2>
                <dl class="grid grid-cols-2 gap-y-2 text-sm">
                    @foreach ($match->statistics as $label => $value)
                        <dt class="text-slate-500 dark:text-slate-400">{{ Str::headline($label) }}</dt>
                        <dd class="text-right font-medium">{{ is_array($value) ? implode(' - ', $value) : $value }}</dd>
                    @endforeach
                </dl>
            </div>
        @endif

        {{-- Predictions --}}
        @if ($predictions->isNotEmpty())
            <div class="mt-8 space-y-4">
                <h2 class="font-semibold">Predictions</h2>
                @foreach ($predictions as $prediction)
                    <x-prediction-card :prediction="$prediction" />
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-sm text-slate-400">
                No prediction has been published for this match yet.
            </div>
        @endif

        {{-- Odds (display only — never a betting action) --}}
        @if ($oddsByMarket->isNotEmpty())
            <div class="mt-8 space-y-4">
                <h2 class="font-semibold">Odds</h2>
                @foreach ($oddsByMarket as $marketName => $odds)
                    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4">
                        <p class="text-sm font-medium mb-2">{{ $marketName }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($odds as $odd)
                                <span class="text-xs rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">
                                    {{ $odd->selection }}: <strong>{{ $odd->price }}</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <p class="text-xs text-slate-400">Odds are for information only. Field Forecast does not accept wagers.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
