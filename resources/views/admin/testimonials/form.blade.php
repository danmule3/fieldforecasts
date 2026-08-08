<x-layouts.admin :title="$testimonial->exists ? 'Edit testimonial' : 'Add testimonial'">
    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $testimonial->name)" required />
        <x-input label="Role / description" name="role" :value="old('role', $testimonial->role)" placeholder="Premium subscriber" />

        <div>
            <label class="block text-sm font-medium mb-1">Quote</label>
            <textarea name="quote" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>{{ old('quote', $testimonial->quote) }}</textarea>
        </div>

        <x-input label="Rating (1-5, optional)" name="rating" type="number" min="1" max="5" :value="old('rating', $testimonial->rating)" />

        <div>
            <label class="block text-sm font-medium mb-1">Avatar</label>
            <input type="file" name="avatar" accept="image/*" class="text-sm">
        </div>

        <x-input label="Display order" name="display_order" type="number" :value="old('display_order', $testimonial->display_order ?? 0)" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.testimonials.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
