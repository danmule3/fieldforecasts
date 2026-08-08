<x-layouts.admin :title="$prediction->exists ? 'Edit prediction' : 'Add prediction'">
    <form method="POST" action="{{ $prediction->exists ? route('admin.predictions.update', $prediction) : route('admin.predictions.store') }}" class="max-w-2xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($prediction->exists) @method('PUT') @endif

        @unless ($prediction->exists)
            <div>
                <label class="block text-sm font-medium mb-1">Match</label>
                <select name="match_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                    @foreach ($matches as $match)
                        <option value="{{ $match->id }}">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} — {{ $match->kickoff_at->format('d M') }}</option>
                    @endforeach
                </select>
            </div>
        @endunless

        <div>
            <label class="block text-sm font-medium mb-1">Market</label>
            <select name="market_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($markets as $market)
                    <option value="{{ $market->id }}" @selected(old('market_id', $prediction->market_id) == $market->id)>{{ $market->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-input label="Pick" name="pick" :value="old('pick', $prediction->pick)" placeholder="e.g. Home" required />
            <x-input label="Confidence %" name="confidence" type="number" min="0" max="100" :value="old('confidence', $prediction->confidence)" required />
        </div>

        <x-input label="Odds at publish (optional)" name="odds_at_publish" type="number" step="0.01" :value="old('odds_at_publish', $prediction->odds_at_publish)" />

        <div>
            <label class="block text-sm font-medium mb-1">Analysis</label>
            <textarea name="analysis" rows="4" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>{{ old('analysis', $prediction->analysis) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Reasoning</label>
            <textarea name="reasoning" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('reasoning', $prediction->reasoning) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Recent form</label>
                <textarea name="recent_form_summary" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('recent_form_summary', $prediction->recent_form_summary) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Head to head</label>
                <textarea name="head_to_head_summary" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('head_to_head_summary', $prediction->head_to_head_summary) }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Injury notes</label>
            <textarea name="injury_notes" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('injury_notes', $prediction->injury_notes) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_premium" value="1" @checked(old('is_premium', $prediction->is_premium ?? false)) class="rounded border-slate-300 text-indigo-600">
            Premium prediction
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.predictions.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
