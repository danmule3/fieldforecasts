<x-layouts.app :title="$league->name" :description="$league->name . ' fixtures, predictions and standings.'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="[
            ['label' => $league->sport->name, 'url' => route('sports.show', $league->sport)],
            ['label' => $league->name, 'url' => null],
        ]" />

        <div class="flex items-center gap-3 mb-8">
            <h1 class="text-2xl font-bold">{{ $league->name }}</h1>
            @if ($league->country)
                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $league->country->name }}</span>
            @endif
            @if ($league->season)
                <span class="text-xs rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5">{{ $league->season }}</span>
            @endif
        </div>

        @if ($standings->isNotEmpty())
            <div class="mb-8 bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                        <tr><th class="px-3 py-2">#</th><th class="px-3 py-2">Team</th><th class="px-3 py-2">P</th><th class="px-3 py-2">W</th><th class="px-3 py-2">D</th><th class="px-3 py-2">L</th><th class="px-3 py-2">GD</th><th class="px-3 py-2">Pts</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($standings as $standing)
                            <tr>
                                <td class="px-3 py-2">{{ $standing->position }}</td>
                                <td class="px-3 py-2 font-medium">
                                    <a href="{{ route('teams.show', $standing->team) }}" class="hover:underline">{{ $standing->team->name }}</a>
                                </td>
                                <td class="px-3 py-2">{{ $standing->played }}</td>
                                <td class="px-3 py-2">{{ $standing->won }}</td>
                                <td class="px-3 py-2">{{ $standing->drawn }}</td>
                                <td class="px-3 py-2">{{ $standing->lost }}</td>
                                <td class="px-3 py-2">{{ $standing->goalDifference() }}</td>
                                <td class="px-3 py-2 font-semibold">{{ $standing->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @forelse ($matches as $match)
                <x-match-card :match="$match" />
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No fixtures found for this league yet.</p>
            @endforelse
        </div>

        {{ $matches->links() }}
    </div>
</x-layouts.app>
