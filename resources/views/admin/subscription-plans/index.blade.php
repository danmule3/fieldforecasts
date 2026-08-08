<x-layouts.admin :title="'Subscription plans'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Subscription plans</h2>
        <a href="{{ route('admin.subscription-plans.create') }}"><x-button>+ Add plan</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Period</th><th class="px-4 py-3">Price</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($plans as $plan)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $plan->name }}</td>
                        <td class="px-4 py-3">{{ ucfirst($plan->billing_period) }} ({{ $plan->duration_days }}d)</td>
                        <td class="px-4 py-3">{{ $plan->priceFormatted() }}</td>
                        <td class="px-4 py-3">{{ $plan->is_active ? 'Active' : 'Disabled' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            @if ($plan->is_active)
                                <form method="POST" action="{{ route('admin.subscription-plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Disable this plan? Existing subscribers keep access.');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400">Disable</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
