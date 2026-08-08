<x-layouts.admin :title="'Leagues'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Leagues</h2>
        <a href="{{ route('admin.leagues.create') }}"><x-button>+ Add league</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Sport</th><th class="px-4 py-3">Country</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($leagues as $league)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $league->name }} @if($league->is_featured) <span class="text-amber-500">★</span> @endif</td>
                        <td class="px-4 py-3">{{ $league->sport->name }}</td>
                        <td class="px-4 py-3">{{ $league->country->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $league->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.standings.index', $league) }}" class="text-indigo-600 dark:text-indigo-400">Standings</a>
                            <a href="{{ route('admin.leagues.edit', $league) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.leagues.destroy', $league) }}" class="inline" onsubmit="return confirm('Delete this league?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $leagues->links() }}
</x-layouts.admin>
