<x-layouts.admin :title="'Tags'">
    <h2 class="text-lg font-semibold mb-6">Tags</h2>

    <form method="POST" action="{{ route('admin.tags.store') }}" class="flex gap-3 mb-6">
        @csrf
        <input type="text" name="name" placeholder="New tag name" required
               class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
        <x-button type="submit">Add tag</x-button>
    </form>

    <div class="flex flex-wrap gap-2">
        @foreach ($tags as $tag)
            <span class="inline-flex items-center gap-2 text-xs rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1.5">
                {{ $tag->name }}
                <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Delete this tag?');">
                    @csrf @method('DELETE')
                    <button class="text-red-500">&times;</button>
                </form>
            </span>
        @endforeach
    </div>
</x-layouts.admin>
