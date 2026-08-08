<x-layouts.app :title="$team->name" :description="$team->name . ' fixtures, form and match predictions.'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold">{{ $team->name }}</h1>
                @if ($team->country)
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $team->country->name }}</p>
                @endif
            </div>

            @auth
                <form method="POST" action="{{ route('teams.follow', $team) }}">
                    @csrf
                    <x-button variant="{{ $isFollowing ? 'secondary' : 'primary' }}">
                        {{ $isFollowing ? 'Following' : '+ Follow team' }}
                    </x-button>
                </form>
            @endauth
        </div>

        <h2 class="font-semibold mb-4">Upcoming fixtures</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($upcoming as $match)
                <x-match-card :match="$match" />
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No upcoming fixtures scheduled.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
