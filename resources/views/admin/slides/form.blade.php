<x-layouts.admin :title="$slide->exists ? 'Edit slide' : 'Add slide'">
    <form method="POST" action="{{ $slide->exists ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($slide->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium mb-1">Placement</label>
            <select name="placement" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                <option value="homepage_hero" @selected(old('placement', $slide->placement) === 'homepage_hero')>Homepage hero (rotating slider)</option>
                <option value="homepage_banner" @selected(old('placement', $slide->placement) === 'homepage_banner')>Homepage banner (static strip)</option>
            </select>
        </div>

        <x-input label="Title (optional)" name="title" :value="old('title', $slide->title)" />
        <x-input label="Subtitle (optional)" name="subtitle" :value="old('subtitle', $slide->subtitle)" />
        <x-input label="Link URL (optional)" name="link_url" :value="old('link_url', $slide->link_url)" />

        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="text-sm" @if(!$slide->exists) required @endif>
            @if ($slide->image_path)
                <img src="{{ Storage::url($slide->image_path) }}" class="mt-2 h-16 rounded-lg">
            @endif
        </div>

        <x-input label="Display order" name="display_order" type="number" :value="old('display_order', $slide->display_order ?? 0)" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slide->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.slides.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
