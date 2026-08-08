<x-layouts.admin :title="'Slides & banners'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Slides &amp; banners</h2>
        <a href="{{ route('admin.slides.create') }}"><x-button>+ Add slide</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Image</th><th class="px-4 py-3">Title</th><th class="px-4 py-3">Placement</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($slides as $slide)
                    <tr>
                        <td class="px-4 py-3"><img src="{{ Storage::url($slide->image_path) }}" class="h-10 rounded"></td>
                        <td class="px-4 py-3 font-medium">{{ $slide->title ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $slide->placement === 'homepage_hero' ? 'Homepage hero (slider)' : 'Homepage banner' }}</td>
                        <td class="px-4 py-3">{{ $slide->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.slides.edit', $slide) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}" class="inline" onsubmit="return confirm('Delete this slide?');">
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
