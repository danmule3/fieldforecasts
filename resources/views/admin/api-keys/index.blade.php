<x-layouts.admin :title="'API Keys'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">API Keys</h2>
        <a href="{{ route('admin.api-keys.create') }}"><x-button>+ Add API key</x-button></a>
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
        Until an active key exists for a provider, Field Forecast runs entirely on manually-entered data — no
        sync job makes an outbound request without one.
    </p>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Provider</th><th class="px-4 py-3">Label</th><th class="px-4 py-3">Key</th><th class="px-4 py-3">Last used</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($apiKeys as $apiKey)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $apiKey->provider }}</td>
                        <td class="px-4 py-3">{{ $apiKey->label }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $apiKey->masked() }}</td>
                        <td class="px-4 py-3 text-slate-400">{{ $apiKey->last_used_at?->diffForHumans() ?? 'Never' }}</td>
                        <td class="px-4 py-3">{{ $apiKey->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <form method="POST" action="{{ route('admin.api-keys.toggle-active', $apiKey) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-indigo-600 dark:text-indigo-400">{{ $apiKey->is_active ? 'Deactivate' : 'Activate' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.api-keys.destroy', $apiKey) }}" class="inline" onsubmit="return confirm('Remove this API key?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
