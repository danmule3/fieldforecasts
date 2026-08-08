<x-layouts.app :title="__('My subscription')">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-8">Subscription &amp; billing history</h1>

        <div class="space-y-4">
            @forelse ($subscriptions as $subscription)
                <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="font-semibold">{{ $subscription->plan->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                @if ($subscription->starts_at)
                                    {{ $subscription->starts_at->format('d M Y') }} – {{ $subscription->ends_at->format('d M Y') }}
                                @else
                                    Not yet activated
                                @endif
                            </p>
                        </div>

                        @php
                            $statusStyles = [
                                'active' => 'bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300',
                                'pending' => 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300',
                                'expired' => 'bg-slate-100 dark:bg-slate-800 text-slate-500',
                                'cancelled' => 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300',
                            ];
                        @endphp
                        <span class="text-xs font-semibold rounded-full px-2.5 py-1 {{ $statusStyles[$subscription->status] }}">
                            {{ ucfirst($subscription->status) }}
                            @if ($subscription->status === 'active' && $subscription->cancelled_at) (not renewing) @endif
                        </span>
                    </div>

                    @if ($subscription->payments->isNotEmpty())
                        <div class="mt-3 border-t border-slate-100 dark:border-slate-800 pt-3 text-sm text-slate-500 dark:text-slate-400">
                            @foreach ($subscription->payments as $payment)
                                <div class="flex justify-between">
                                    <span>{{ $payment->paid_at?->format('d M Y') ?? 'Pending' }}</span>
                                    <span>{{ $payment->amountFormatted() }} &middot; {{ ucfirst($payment->status) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($subscription->isActive() && ! $subscription->cancelled_at)
                        <form method="POST" action="{{ route('subscriptions.cancel', $subscription) }}" class="mt-4"
                              onsubmit="return confirm('Cancel auto-renew for this subscription?');">
                            @csrf
                            <x-button variant="secondary">Cancel auto-renew</x-button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    You don't have any subscriptions yet. <a href="{{ route('subscriptions.index') }}" class="text-indigo-600 dark:text-indigo-400">View plans &rarr;</a>
                </p>
            @endforelse
        </div>

        {{ $subscriptions->links() }}
    </div>
</x-layouts.app>
