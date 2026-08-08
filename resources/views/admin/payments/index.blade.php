<x-layouts.admin :title="'Payments'">
    <h2 class="text-lg font-semibold mb-6">Payments</h2>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach (['pending', 'completed', 'failed', 'refunded'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">User</th><th class="px-4 py-3">Plan</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Date</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">{{ $payment->user->name }}</td>
                        <td class="px-4 py-3">{{ $payment->subscription?->plan?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $payment->amountFormatted() }}</td>
                        <td class="px-4 py-3">{{ ucfirst($payment->status) }}</td>
                        <td class="px-4 py-3">{{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
</x-layouts.admin>
