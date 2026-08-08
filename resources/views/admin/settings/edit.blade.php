<x-layouts.admin :title="'Settings'">
    <h2 class="text-lg font-semibold mb-6">Site settings</h2>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf @method('PUT')

        <x-input label="Site name" name="site_name" :value="old('site_name', $settings->get('site_name', 'Field Forecast'))" required />
        <x-input label="Contact email" name="contact_email" type="email" :value="old('contact_email', $settings->get('contact_email'))" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $settings->get('maintenance_mode', false))) class="rounded border-slate-300 text-indigo-600">
            Maintenance mode
        </label>
        <p class="text-xs text-slate-400">
            This flag is available to views/middleware via SettingsService — wiring an actual maintenance-mode
            middleware that reads it is a one-line addition when needed; it's informational only for now.
        </p>

        <x-button type="submit">Save settings</x-button>
    </form>
</x-layouts.admin>
