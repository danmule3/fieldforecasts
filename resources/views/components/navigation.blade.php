<nav x-data="{ mobileOpen: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-slate-950/80 backdrop-blur border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Field Forecast" class="h-9 w-auto">
            </a>

            {{-- Blog nav link is added in Module 6 --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('sports.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Sports</a>
                <a href="{{ route('matches.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Matches</a>
                <a href="{{ route('predictions.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Predictions</a>
                <a href="{{ route('subscriptions.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Premium</a>
                <a href="{{ route('articles.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Blog</a>

                <button @click="dark = !dark" type="button" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" aria-label="Toggle dark mode">
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark">☀️</span>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button type="submit" variant="secondary">Log out</x-button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>
                    <a href="{{ route('register') }}">
                        <x-button type="button" variant="primary">Sign up</x-button>
                    </a>
                @endauth
            </div>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2" aria-label="Toggle menu">
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200 mb-1"></span>
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200 mb-1"></span>
                <span class="block w-6 h-0.5 bg-slate-700 dark:bg-slate-200"></span>
            </button>
        </div>

        <div x-show="mobileOpen" x-cloak class="md:hidden pb-4 flex flex-col gap-3 text-sm font-medium">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-left">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}">Log in</a>
                <a href="{{ route('register') }}">Sign up</a>
            @endauth
        </div>
    </div>
</nav>
