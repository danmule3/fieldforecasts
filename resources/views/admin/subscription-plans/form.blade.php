<x-layouts.admin :title="$plan->exists ? 'Edit plan' : 'Add plan'">
    <form method="POST" action="{{ $plan->exists ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}" class="max-w-lg space-y-4 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf
        @if ($plan->exists) @method('PUT') @endif

        <x-input label="Name" name="name" :value="old('name', $plan->name)" required />

        <div>
            <label class="block text-sm font-medium mb-1">Billing period</label>
            <select name="billing_period" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm" required>
                <option value="weekly" @selected(old('billing_period', $plan->billing_period) === 'weekly')>Weekly</option>
                <option value="monthly" @selected(old('billing_period', $plan->billing_period) === 'monthly')>Monthly</option>
            </select>
        </div>

        <x-input label="Duration (days)" name="duration_days" type="number" :value="old('duration_days', $plan->duration_days)" required />
        <x-input label="Price (cents)" name="price_cents" type="number" :value="old('price_cents', $plan->price_cents)" required />
        <x-input label="Currency" name="currency" :value="old('currency', $plan->currency ?? 'USD')" maxlength="3" required />

        <div>
            <label class="block text-sm font-medium mb-1">Features (one per line)</label>
            <textarea name="features" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">{{ old('features', $plan->features ? implode("\n", $plan->features) : '') }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true)) class="rounded border-slate-300 text-indigo-600">
            Active
        </label>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.subscription-plans.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
