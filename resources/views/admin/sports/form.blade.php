<x-layouts.admin :title="$sport->exists ? 'Edit sport' : 'Add sport'">
    <form method="POST" action="{{ $sport->exists ? route('admin.sports.update', $sport) : route('admin.sports.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($sport->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $sport->name)" required />
        <x-input label="Icon key (optional)" name="icon" :value="old('icon', $sport->icon)" />
        <x-input label="Display order" name="display_order" type="number" :value="old('display_order', $sport->display_order ?? 0)" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $sport->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.sports.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
