<x-layouts.admin :title="'Predictions'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Predictions</h2>
        <a href="{{ route('admin.predictions.create') }}"><x-button>+ Add prediction</x-button></a>
    </div>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach (['pending', 'won', 'lost', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Match</th><th class="px-4 py-3">Market / pick</th><th class="px-4 py-3">Confidence</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($predictions as $prediction)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $prediction->match->homeTeam->name }} vs {{ $prediction->match->awayTeam->name }}</td>
                        <td class="px-4 py-3">{{ $prediction->market->name }} — {{ $prediction->pick }}</td>
                        <td class="px-4 py-3">{{ $prediction->confidence }}%</td>
                        <td class="px-4 py-3">{{ ucfirst($prediction->status) }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.predictions.edit', $prediction) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>

                            @if ($prediction->status === 'pending' && auth()->user()->can('settle', $prediction))
                                <span x-data="{ open: false }" class="relative inline-block">
                                    <button @click="open = !open" type="button" class="text-emerald-600 dark:text-emerald-400">Settle</button>
                                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-1 bg-white dark:bg-slate-800 ring-1 ring-slate-900/10 rounded-lg p-2 z-10 w-32 space-y-1">
                                        @foreach (['won' => 'Won', 'lost' => 'Lost', 'cancelled' => 'Cancelled'] as $value => $label)
                                            <form method="POST" action="{{ route('admin.predictions.settle', $prediction) }}">
                                                @csrf
                                                <input type="hidden" name="outcome" value="{{ $value }}">
                                                <button class="w-full text-left text-xs px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-700">{{ $label }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                </span>
                            @endif

                            <form method="POST" action="{{ route('admin.predictions.destroy', $prediction) }}" class="inline" onsubmit="return confirm('Delete this prediction?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $predictions->links() }}
</x-layouts.admin>
