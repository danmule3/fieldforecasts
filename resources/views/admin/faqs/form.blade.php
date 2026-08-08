<x-layouts.admin :title="$faq->exists ? 'Edit FAQ' : 'Add FAQ'">
    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="max-w-xl space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($faq->exists) @method('PUT') @endif

        <x-input label="Question" name="question" :value="old('question', $faq->question)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Answer</label>
            <textarea name="answer" rows="4" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <x-input label="Category (optional grouping)" name="category" :value="old('category', $faq->category)" placeholder="Subscriptions" />
        <x-input label="Display order" name="display_order" type="number" :value="old('display_order', $faq->display_order ?? 0)" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.faqs.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
