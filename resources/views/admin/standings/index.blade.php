<x-layouts.admin :title="'Standings — ' . $league->name">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">{{ $league->name }} standings</h2>
        <a href="{{ route('admin.leagues.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">&larr; Back to leagues</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                    <tr><th class="px-3 py-2">#</th><th class="px-3 py-2">Team</th><th class="px-3 py-2">P</th><th class="px-3 py-2">W</th><th class="px-3 py-2">D</th><th class="px-3 py-2">L</th><th class="px-3 py-2">Pts</th><th class="px-3 py-2"></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($standings as $standing)
                        <tr>
                            <td class="px-3 py-2">{{ $standing->position }}</td>
                            <td class="px-3 py-2 font-medium">{{ $standing->team->name }}</td>
                            <td class="px-3 py-2">{{ $standing->played }}</td>
                            <td class="px-3 py-2">{{ $standing->won }}</td>
                            <td class="px-3 py-2">{{ $standing->drawn }}</td>
                            <td class="px-3 py-2">{{ $standing->lost }}</td>
                            <td class="px-3 py-2 font-semibold">{{ $standing->points }}</td>
                            <td class="px-3 py-2 text-right">
                                <form method="POST" action="{{ route('admin.standings.destroy', [$league, $standing]) }}" onsubmit="return confirm('Remove this row?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 text-xs">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-3 py-4 text-slate-400">No standings entered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h3 class="font-semibold mb-4">Add / update row</h3>
            <form method="POST" action="{{ route('admin.standings.store', $league) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Team</label>
                    <select name="team_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <x-input label="Position" name="position" type="number" min="0" required />
                    <x-input label="Points" name="points" type="number" required />
                    <x-input label="Played" name="played" type="number" min="0" required />
                    <x-input label="Won" name="won" type="number" min="0" required />
                    <x-input label="Drawn" name="drawn" type="number" min="0" required />
                    <x-input label="Lost" name="lost" type="number" min="0" required />
                    <x-input label="Goals for" name="goals_for" type="number" required />
                    <x-input label="Goals against" name="goals_against" type="number" required />
                </div>
                <x-button type="submit" class="w-full">Save row</x-button>
            </form>
        </div>
    </div>
</x-layouts.admin>
