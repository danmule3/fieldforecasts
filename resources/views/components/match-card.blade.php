@props(['match'])

<a href="{{ route('matches.show', $match) }}"
   class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 hover:ring-indigo-500 transition">
    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
        <span>{{ $match->league->name }}</span>
        @if ($match->isLive())
            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                LIVE @if($match->minute) {{ $match->minute }}' @endif
            </span>
        @else
            <span>{{ $match->kickoff_at->format('D, H:i') }}</span>
        @endif
    </div>

    <div class="flex items-center justify-between text-sm font-medium">
        <span class="truncate">{{ $match->homeTeam->short_name ?? $match->homeTeam->name }}</span>
        @if ($match->status === \App\Models\GameMatch::STATUS_FINISHED || $match->isLive())
            <span class="font-bold px-2">{{ $match->home_score }} - {{ $match->away_score }}</span>
        @else
            <span class="text-slate-400 px-2">vs</span>
        @endif
        <span class="truncate text-right">{{ $match->awayTeam->short_name ?? $match->awayTeam->name }}</span>
    </div>
</a>
