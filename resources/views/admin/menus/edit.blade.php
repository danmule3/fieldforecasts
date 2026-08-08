<x-layouts.admin :title="'Edit menu: ' . $menu->name">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">{{ $menu->name }}</h2>
        <a href="{{ route('admin.menus.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">&larr; Back to menus</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h3 class="font-semibold mb-4">Items</h3>
            <div class="space-y-2">
                @forelse ($menu->items as $item)
                    <div class="flex items-center justify-between text-sm border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span>{{ $item->label }} <span class="text-slate-400">({{ $item->url }})</span></span>
                        <form method="POST" action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" onsubmit="return confirm('Remove this item?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 dark:text-red-400 text-xs">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">No items yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h3 class="font-semibold mb-4">Add item</h3>
            <form method="POST" action="{{ route('admin.menus.items.store', $menu) }}" class="space-y-4">
                @csrf
                <x-input label="Label" name="label" required />
                <x-input label="URL" name="url" placeholder="/faq or https://..." required />
                <x-input label="Display order" name="display_order" type="number" value="0" required />
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="opens_new_tab" value="1" class="rounded border-slate-300 text-indigo-600">
                    Open in new tab
                </label>
                <x-button type="submit">Add item</x-button>
            </form>
        </div>
    </div>
</x-layouts.admin>
