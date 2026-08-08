@props(['prediction', 'canView' => null])

@php
    $canView = $canView ?? (auth()->user()?->can('view', $prediction) ?? ! $prediction->is_premium);
@endphp

<div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-4 relative overflow-hidden">
    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-2">
        <span>{{ $prediction->match->league->name ?? '' }}</span>
        @if ($prediction->is_premium)
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-2 py-0.5 font-semibold">★ Premium</span>
        @endif
    </div>

    <a href="{{ route('predictions.show', $prediction) }}" class="font-semibold hover:underline">
        {{ $prediction->match->homeTeam->name }} vs {{ $prediction->match->awayTeam->name }}
    </a>

    <div class="mt-3 flex items-center justify-between text-sm">
        <span class="text-slate-500 dark:text-slate-400">{{ $prediction->market->name ?? '' }}</span>
        <span class="font-bold">{{ $prediction->confidence }}% confidence</span>
    </div>

    <div class="mt-3 relative">
        <p class="text-sm text-slate-600 dark:text-slate-300 {{ $canView ? '' : 'blur-sm select-none' }}">
            {{ Str::limit($prediction->analysis, 140) }}
        </p>

        @unless ($canView)
            <div class="absolute inset-0 flex items-center justify-center">
                <a href="{{ route('predictions.show', $prediction) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-white/90 dark:bg-slate-900/90 rounded-full px-3 py-1">
                    Unlock with Premium
                </a>
            </div>
        @endunless
    </div>

    @if ($prediction->status !== \App\Models\Prediction::STATUS_PENDING)
        <div class="mt-3">
            @php
                $statusStyles = [
                    'won' => 'text-emerald-600 dark:text-emerald-400',
                    'lost' => 'text-red-600 dark:text-red-400',
                    'cancelled' => 'text-slate-400',
                ];
            @endphp
            <span class="text-xs font-semibold {{ $statusStyles[$prediction->status] }}">{{ ucfirst($prediction->status) }}</span>
        </div>
    @endif
</div>
