<x-layouts.app :title="__('Premium Subscription')" :description="__('Weekly and monthly premium plans unlocking full match prediction analysis.')">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-center mb-2">Go Premium</h1>
        <p class="text-slate-500 dark:text-slate-400 text-center mb-10">
            Unlock full analysis, reasoning, and statistics on every prediction.
        </p>

        @session('status')
            @if (session('status') === 'subscription-cancelled')
                <x-alert type="info" class="mb-6">Your subscription won't renew, but you'll keep access until it ends.</x-alert>
            @endif
        @endsession

        @error('plan')
            <x-alert type="error" class="mb-6">{{ $message }}</x-alert>
        @enderror

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach ($plans as $plan)
                <div class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-8 flex flex-col">
                    <h2 class="text-lg font-bold">{{ $plan->name }}</h2>
                    <p class="text-3xl font-extrabold mt-2">{{ $plan->priceFormatted() }}
                        <span class="text-sm font-normal text-slate-400">/ {{ $plan->billing_period }}</span>
                    </p>

                    @if ($plan->features)
                        <ul class="mt-6 space-y-2 text-sm text-slate-600 dark:text-slate-300 flex-1">
                            @foreach ($plan->features as $feature)
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-500">✓</span> {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('subscriptions.store') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $plan->slug }}">
                            <x-button type="submit" class="w-full">Subscribe</x-button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="mt-6">
                            <x-button class="w-full">Sign up to subscribe</x-button>
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-xs text-slate-400 text-center">
            Cancel anytime — you'll keep premium access until the end of your current billing period. No annual plan.
        </p>
    </div>
</x-layouts.app>
