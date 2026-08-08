<x-layouts.app :title="__('Dashboard')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-1">Welcome back, {{ $user->name }}</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-8">
            @if ($user->hasActivePremiumAccess())
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-2.5 py-0.5 text-xs font-semibold">★ Premium</span>
            @else
                Free account
            @endif
        </p>

        {{-- Reserved regions for Modules 2–4: Favourite Teams, Saved
             Predictions, Subscription status, Billing history --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Subscription</h2>
                @if ($currentSubscription)
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ $currentSubscription->plan->name }} — renews/ends {{ $currentSubscription->ends_at->format('d M Y') }}
                    </p>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">No active subscription.</p>
                @endif
                <a href="{{ route('subscriptions.mine') }}" class="text-sm text-indigo-600 dark:text-indigo-400 mt-2 inline-block">
                    {{ $currentSubscription ? 'Manage subscription' : 'View plans' }} &rarr;
                </a>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Favourite teams</h2>
                @if ($user->favouriteTeams->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        You haven't followed any teams yet.
                        <a href="{{ route('sports.index') }}" class="text-indigo-600 dark:text-indigo-400">Browse sports &rarr;</a>
                    </p>
                @else
                    <ul class="space-y-1 text-sm">
                        @foreach ($user->favouriteTeams as $team)
                            <li><a href="{{ route('teams.show', $team) }}" class="hover:underline">{{ $team->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
                <h2 class="font-semibold mb-2">Saved predictions</h2>
                @if ($user->savedPredictions->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        You haven't saved any predictions yet.
                        <a href="{{ route('predictions.index') }}" class="text-indigo-600 dark:text-indigo-400">Browse predictions &rarr;</a>
                    </p>
                @else
                    <ul class="space-y-1 text-sm">
                        @foreach ($user->savedPredictions as $prediction)
                            <li>
                                <a href="{{ route('predictions.show', $prediction) }}" class="hover:underline">
                                    {{ $prediction->match->homeTeam->name }} vs {{ $prediction->match->awayTeam->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('profile.edit') }}" class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">Edit profile &rarr;</a>
        </div>
    </div>
</x-layouts.app>
