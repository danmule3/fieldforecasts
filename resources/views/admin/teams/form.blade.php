<x-layouts.admin :title="$team->exists ? 'Edit team' : 'Add team'">
    <form method="POST" action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($team->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $team->name)" required />
        <x-input label="Short name (e.g. ARS)" name="short_name" :value="old('short_name', $team->short_name)" maxlength="10" />

        <div>
            <label class="block text-sm font-medium mb-1">Sport</label>
            <select name="sport_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected(old('sport_id', $team->sport_id) == $sport->id)>{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Country</label>
            <select name="country_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected(old('country_id', $team->country_id) == $country->id)>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.teams.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
