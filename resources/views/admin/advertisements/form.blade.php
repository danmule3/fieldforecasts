<x-layouts.admin :title="$advertisement->exists ? 'Edit advertisement' : 'Add advertisement'">
    <form method="POST" action="{{ $advertisement->exists ? route('admin.advertisements.update', $advertisement) : route('admin.advertisements.store') }}" enctype="multipart/form-data" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($advertisement->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $advertisement->name)" required />
        <x-input label="Placement key" name="placement" :value="old('placement', $advertisement->placement)" placeholder="homepage_sidebar" required />
        <x-input label="Target URL" name="target_url" type="url" :value="old('target_url', $advertisement->target_url)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="text-sm" @if(!$advertisement->exists) required @endif>
            @if ($advertisement->image_path)
                <img src="{{ Storage::url($advertisement->image_path) }}" class="mt-2 h-16 rounded-lg">
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-input label="Starts (optional)" name="starts_at" type="date" :value="old('starts_at', $advertisement->starts_at?->format('Y-m-d'))" />
            <x-input label="Ends (optional)" name="ends_at" type="date" :value="old('ends_at', $advertisement->ends_at?->format('Y-m-d'))" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $advertisement->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.advertisements.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
