<x-layouts.admin :title="'Advertisements'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Advertisements</h2>
        <a href="{{ route('admin.advertisements.create') }}"><x-button>+ Add advertisement</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Placement</th><th class="px-4 py-3">Window</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($advertisements as $ad)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $ad->name }}</td>
                        <td class="px-4 py-3">{{ $ad->placement }}</td>
                        <td class="px-4 py-3 text-xs">
                            {{ $ad->starts_at?->format('d M Y') ?? 'Always' }} – {{ $ad->ends_at?->format('d M Y') ?? 'Ongoing' }}
                        </td>
                        <td class="px-4 py-3">{{ $ad->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.advertisements.edit', $ad) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.advertisements.destroy', $ad) }}" class="inline" onsubmit="return confirm('Delete this advertisement?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
