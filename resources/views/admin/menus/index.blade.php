<x-layouts.admin :title="'Menus'">
    <h2 class="text-lg font-semibold mb-6">Menus</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach ($menus as $menu)
            <a href="{{ route('admin.menus.edit', $menu) }}" class="block bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5 hover:ring-indigo-500 transition">
                <p class="font-semibold">{{ $menu->name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $menu->items->count() }} item(s)</p>
            </a>
        @endforeach
    </div>
</x-layouts.admin>
