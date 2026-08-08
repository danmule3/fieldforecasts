<x-layouts.admin :title="'Pages'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Pages</h2>
        <a href="{{ route('admin.pages.create') }}"><x-button>+ Add page</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($pages as $page)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $page->title }}</td>
                        <td class="px-4 py-3">{{ $page->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            @if ($page->is_active)
                                <a href="{{ route('pages.show', $page) }}" class="text-slate-500">View</a>
                            @endif
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" onsubmit="return confirm('Delete this page?');">
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
