<x-layouts.admin :title="$country->exists ? 'Edit country' : 'Add country'">
    <form method="POST" action="{{ $country->exists ? route('admin.countries.update', $country) : route('admin.countries.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($country->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $country->name)" required />
        <x-input label="ISO code" name="iso_code" :value="old('iso_code', $country->iso_code)" maxlength="3" />

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.countries.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
