<x-layouts.admin :title="'Edit section: ' . ($section->title ?? $section->section_key)">
    <div class="mb-6">
        <a href="{{ route('admin.page-sections.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">&larr; Back to sections</a>
    </div>

    <form method="POST" action="{{ route('admin.page-sections.update', $section) }}" class="max-w-2xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @method('PUT')

        <p class="text-xs text-slate-400">Type: {{ $section->type }} &middot; ID: {{ $section->section_key }}</p>

        <x-input label="Section label (admin-only, shown in the sections list)" name="title" :value="old('title', $section->title)" />

        <div>
            <label class="block text-sm font-medium mb-1">Admin note / description (optional)</label>
            <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('description', $section->description) }}</textarea>
        </div>

        @if ($section->type === 'hero')
            <div>
                <label class="block text-sm font-medium mb-1">Headline</label>
                <textarea name="headline" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('headline', $section->content['headline'] ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Subheadline</label>
                <textarea name="subheadline" rows="2" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('subheadline', $section->content['subheadline'] ?? '') }}</textarea>
            </div>
        @endif

        @if ($section->type === 'stats')
            <div>
                <label class="block text-sm font-medium mb-1">Stats — one "label | value" per line</label>
                <textarea name="items_raw" rows="4" placeholder="Predictions published | 1,240&#10;Overall accuracy | 68%"
                          class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm font-mono">{{ old('items_raw', collect($section->content['items'] ?? [])->map(fn ($i) => ($i['label'] ?? '') . ' | ' . ($i['value'] ?? ''))->implode("\n")) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">The bar is hidden automatically if this is left empty.</p>
            </div>
        @endif

        @if ($section->type === 'features')
            <div>
                <label class="block text-sm font-medium mb-1">Feature items — one "icon | title | text" per line</label>
                <textarea name="items_raw" rows="5" placeholder="📊 | Data-driven | Every prediction backed by statistics."
                          class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm font-mono">{{ old('items_raw', collect($section->content['items'] ?? [])->map(fn ($i) => ($i['icon'] ?? '') . ' | ' . ($i['title'] ?? '') . ' | ' . ($i['text'] ?? ''))->implode("\n")) }}</textarea>
            </div>
        @endif

        @if (in_array($section->type, ['live_matches', 'todays_predictions', 'today_matches', 'featured_matches', 'upcoming_matches', 'recent_winners', 'latest_articles', 'testimonials'], true))
            <p class="text-xs text-slate-400 bg-slate-50 dark:bg-slate-800 rounded-lg p-3">
                This section's content (matches, predictions, articles, etc.) comes from live data elsewhere on the site — only the heading and visibility are editable here.
            </p>
        @endif

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.page-sections.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
