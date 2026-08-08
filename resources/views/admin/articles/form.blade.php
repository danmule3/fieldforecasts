<x-layouts.admin :title="$article->exists ? 'Edit article' : 'Add article'">
    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <x-input label="Title" name="title" :value="old('title', $article->title)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="category_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <x-input label="Tags (comma-separated)" name="tags" :value="old('tags', $selectedTags)" placeholder="analysis, premier-league" />

        <div>
            <label class="block text-sm font-medium mb-1">Excerpt</label>
            <textarea name="excerpt" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Body</label>
            <textarea name="body" rows="12" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>{{ old('body', $article->body) }}</textarea>
            <p class="text-xs text-slate-400 mt-1">Plain text — rendered with line breaks, no raw HTML (prevents stored XSS from article content).</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Featured image</label>
            <input type="file" name="featured_image" accept="image/*" class="text-sm">
            @if ($article->featured_image_path)
                <img src="{{ Storage::url($article->featured_image_path) }}" class="mt-2 h-24 rounded-lg">
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $article->status) === 'published')>Published</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-input label="Meta title (SEO)" name="meta_title" :value="old('meta_title', $article->meta_title)" maxlength="60" />
            <x-input label="Meta description (SEO)" name="meta_description" :value="old('meta_description', $article->meta_description)" maxlength="160" />
        </div>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.articles.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
