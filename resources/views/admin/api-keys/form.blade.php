<x-layouts.admin :title="'Add API key'">
    <form method="POST" action="{{ route('admin.api-keys.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Provider</label>
            <select name="provider" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                @foreach ($providers as $provider)
                    <option value="{{ $provider }}">{{ $provider }}</option>
                @endforeach
            </select>
        </div>

        <x-input label="Label" name="label" placeholder="Primary fixtures key" required />
        <x-input label="API key value" name="key_value" type="password" required />
        <p class="text-xs text-slate-400">Stored encrypted at rest. Never displayed in full again after saving.</p>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.api-keys.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
