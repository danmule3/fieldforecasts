<x-layouts.admin :title="$league->exists ? 'Edit league' : 'Add league'">
    <form method="POST" action="{{ $league->exists ? route('admin.leagues.update', $league) : route('admin.leagues.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($league->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $league->name)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Sport</label>
            <select name="sport_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected(old('sport_id', $league->sport_id) == $sport->id)>{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Country</label>
            <select name="country_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected(old('country_id', $league->country_id) == $country->id)>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <x-input label="Season" name="season" :value="old('season', $league->season)" placeholder="2026/2027" />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $league->is_featured ?? false)) class="rounded border-slate-300 text-indigo-600">
            Featured
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $league->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.leagues.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
