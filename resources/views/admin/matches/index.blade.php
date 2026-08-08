<x-layouts.admin :title="'Matches'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Matches</h2>
        <a href="{{ route('admin.matches.create') }}"><x-button>+ Add match</x-button></a>
    </div>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach (['scheduled', 'live', 'finished', 'postponed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Match</th><th class="px-4 py-3">League</th><th class="px-4 py-3">Kickoff</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($matches as $match)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</td>
                        <td class="px-4 py-3">{{ $match->league->name }}</td>
                        <td class="px-4 py-3">{{ $match->kickoff_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">{{ ucfirst($match->status) }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.odds.index', $match) }}" class="text-indigo-600 dark:text-indigo-400">Odds</a>
                            <a href="{{ route('admin.matches.edit', $match) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.matches.destroy', $match) }}" class="inline" onsubmit="return confirm('Delete this match?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $matches->links() }}
</x-layouts.admin>
