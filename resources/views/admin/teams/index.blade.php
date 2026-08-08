<x-layouts.admin :title="'Teams'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Teams</h2>
        <a href="{{ route('admin.teams.create') }}"><x-button>+ Add team</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Sport</th><th class="px-4 py-3">Country</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($teams as $team)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $team->name }}</td>
                        <td class="px-4 py-3">{{ $team->sport->name }}</td>
                        <td class="px-4 py-3">{{ $team->country->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.teams.edit', $team) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="inline" onsubmit="return confirm('Delete this team?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $teams->links() }}
</x-layouts.admin>
