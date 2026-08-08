<x-layouts.admin :title="'Newsletter'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Newsletter subscribers ({{ $activeCount }} active)</h2>
        <a href="{{ route('admin.newsletter.export') }}"><x-button variant="secondary">Export CSV</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Email</th><th class="px-4 py-3">Subscribed</th><th class="px-4 py-3">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($subscribers as $subscriber)
                    <tr>
                        <td class="px-4 py-3">{{ $subscriber->email }}</td>
                        <td class="px-4 py-3">{{ $subscriber->subscribed_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $subscriber->unsubscribed_at ? 'Unsubscribed' : 'Active' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $subscribers->links() }}
</x-layouts.admin>
