<x-layouts.admin :title="'Activity logs'">
    <h2 class="text-lg font-semibold mb-6">Activity logs</h2>

    <form method="GET" class="mb-4">
        <input type="text" name="event" value="{{ request('event') }}" placeholder="Filter by event, e.g. auth.login"
               class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm w-72">
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Event</th><th class="px-4 py-3">User</th><th class="px-4 py-3">IP</th><th class="px-4 py-3">When</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $log->event }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $log->ip_address }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</x-layouts.admin>
