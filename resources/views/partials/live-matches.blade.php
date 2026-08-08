@if ($matches->isEmpty())
    <p class="text-sm text-slate-500 dark:text-slate-400">No matches are live right now.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($matches as $match)
            <x-match-card :match="$match" />
        @endforeach
    </div>
@endif
