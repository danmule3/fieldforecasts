<x-layouts.admin :title="'Dashboard'">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['label' => 'Registered users', 'value' => number_format($stats['registered_users']), 'icon' => '👤', 'color' => 'bg-indigo-600'],
            ['label' => 'Premium users', 'value' => number_format($stats['premium_users']), 'icon' => '⭐', 'color' => 'bg-amber-500'],
            ['label' => 'Active subscriptions', 'value' => number_format($stats['active_subscriptions']), 'icon' => '💳', 'color' => 'bg-emerald-600'],
            ['label' => 'Revenue', 'value' => number_format($stats['revenue_cents'] / 100, 2) . ' USD', 'icon' => '💰', 'color' => 'bg-purple-600'],
        ] as $card)
            <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
                <div class="w-9 h-9 rounded-lg {{ $card['color'] }} text-white flex items-center justify-center text-base mb-3">{{ $card['icon'] }}</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold mt-1">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Predictions</p>
            <p class="text-lg font-semibold">{{ $stats['total_predictions'] }} total &middot; {{ $stats['settled_predictions'] }} settled</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Subscription growth</p>
            <p class="text-lg font-semibold">
                {{ $stats['subscriptions_this_month'] }} this month
                <span class="text-sm text-slate-400">(vs {{ $stats['subscriptions_last_month'] }} last month)</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <h2 class="font-semibold mb-3">Most viewed predictions</h2>
            <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($mostViewed as $prediction)
                    <li class="py-2 flex justify-between">
                        <a href="{{ route('predictions.show', $prediction) }}" class="hover:underline truncate">
                            {{ $prediction->match->homeTeam->name }} vs {{ $prediction->match->awayTeam->name }}
                        </a>
                        <span class="text-slate-400">{{ $prediction->views_count }} views</span>
                    </li>
                @empty
                    <li class="py-2 text-slate-400">No views recorded yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
            <h2 class="font-semibold mb-3">Popular sports</h2>
            <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($popularSports as $sport)
                    <li class="py-2 flex justify-between">
                        <span>{{ $sport->name }}</span>
                        <span class="text-slate-400">{{ $sport->matches_count }} matches</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Recent activity</h2>
            <a href="{{ route('admin.logs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">View all &rarr;</a>
        </div>
        <ul class="text-sm divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($recentActivity as $log)
                <li class="py-2 flex justify-between">
                    <span>{{ $log->user?->name ?? 'System' }} &mdash; {{ $log->event }}</span>
                    <span class="text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="py-2 text-slate-400">No activity recorded yet.</li>
            @endforelse
        </ul>
    </div>
</x-layouts.admin>
