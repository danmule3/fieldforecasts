<x-layouts.admin :title="$page->exists ? 'Edit page' : 'Add page'">
    <form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="max-w-2xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <x-input label="Title" name="title" :value="old('title', $page->title)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Body</label>
            <textarea name="body" rows="10" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>{{ old('body', $page->body) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-input label="Meta title (SEO)" name="meta_title" :value="old('meta_title', $page->meta_title)" maxlength="60" />
            <x-input label="Meta description (SEO)" name="meta_description" :value="old('meta_description', $page->meta_description)" maxlength="160" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.pages.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
