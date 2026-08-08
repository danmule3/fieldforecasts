<x-layouts.admin :title="$market->exists ? 'Edit market' : 'Add market'">
    <form method="POST" action="{{ $market->exists ? route('admin.markets.update', $market) : route('admin.markets.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($market->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $market->name)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Sport (leave blank to apply to all sports)</label>
            <select name="sport_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">All sports</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected(old('sport_id', $market->sport_id) == $sport->id)>{{ $sport->name }}</option>
                @endforeach
            </select>
        </div>

        <x-input label="Display order" name="display_order" type="number" :value="old('display_order', $market->display_order ?? 0)" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $market->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.markets.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
