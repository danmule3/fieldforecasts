<x-layouts.admin :title="'Homepage sections'">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">Home Page — Sections</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Click any section to edit its content. Use the arrows to reorder, or the toggle to show/hide it on the live site.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-3">
            @foreach ($sections as $section)
                <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-lg shrink-0">⚙️</div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-semibold">{{ $section->title ?? $section->section_key }}</p>
                                @if ($section->is_visible)
                                    <span class="inline-flex items-center gap-1 text-xs rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Visible
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Hidden
                                    </span>
                                @endif
                            </div>
                            @if ($section->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $section->description }}</p>
                            @endif
                            <p class="text-xs text-slate-400 mt-1">ID: {{ $section->section_key }} &middot; Type: {{ $section->type }} &middot; Order: {{ $section->display_order }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.page-sections.move-up', $section) }}">
                            @csrf @method('PATCH')
                            <button class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" title="Move up">&uarr;</button>
                        </form>
                        <form method="POST" action="{{ route('admin.page-sections.move-down', $section) }}">
                            @csrf @method('PATCH')
                            <button class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" title="Move down">&darr;</button>
                        </form>
                        <form method="POST" action="{{ route('admin.page-sections.toggle-visible', $section) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs rounded-lg px-2 py-1 ring-1 ring-slate-300 dark:ring-slate-700">
                                {{ $section->is_visible ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.page-sections.edit', $section) }}" class="text-xs rounded-lg px-3 py-1 bg-indigo-600 text-white">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5 h-fit">
            <p class="text-xs font-semibold text-slate-400 uppercase mb-3">Quick stats</p>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Total sections</dt><dd class="font-semibold">{{ $totalCount }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Active</dt><dd class="font-semibold text-emerald-600">{{ $activeCount }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Hidden</dt><dd class="font-semibold text-slate-400">{{ $hiddenCount }}</dd></div>
            </dl>
        </div>
    </div>
</x-layouts.admin>
