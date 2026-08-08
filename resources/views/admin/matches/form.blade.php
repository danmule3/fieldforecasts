<x-layouts.admin :title="$match->exists ? 'Edit match' : 'Add match'">
    <form method="POST" action="{{ $match->exists ? route('admin.matches.update', $match) : route('admin.matches.store') }}" class="max-w-xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($match->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium mb-1">Sport</label>
            <select name="sport_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected(old('sport_id', $match->sport_id) == $sport->id)>{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">League</label>
            <select name="league_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($leagues as $league)
                    <option value="{{ $league->id }}" @selected(old('league_id', $match->league_id) == $league->id)>{{ $league->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Home team</label>
                <select name="home_team_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('home_team_id', $match->home_team_id) == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Away team</label>
                <select name="away_team_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('away_team_id', $match->away_team_id) == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <x-input label="Kickoff" name="kickoff_at" type="datetime-local"
                  :value="old('kickoff_at', $match->kickoff_at?->format('Y-m-d\TH:i'))" required />
        <x-input label="Venue" name="venue" :value="old('venue', $match->venue)" />

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach (['scheduled', 'live', 'finished', 'postponed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $match->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <x-input label="Home score" name="home_score" type="number" :value="old('home_score', $match->home_score)" />
            <x-input label="Away score" name="away_score" type="number" :value="old('away_score', $match->away_score)" />
            <x-input label="Minute" name="minute" type="number" :value="old('minute', $match->minute)" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $match->is_featured ?? false)) class="rounded border-slate-300 text-indigo-600">
            Featured
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.matches.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
