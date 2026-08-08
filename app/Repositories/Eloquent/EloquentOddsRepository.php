<?php

namespace App\Repositories\Eloquent;

use App\Models\GameMatch;
use App\Models\Odd;
use App\Repositories\Contracts\OddsRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EloquentOddsRepository implements OddsRepositoryInterface
{
    public function forMatch(GameMatch $match): Collection
    {
        // Odds change frequently pre-kickoff; short cache absorbs
        // repeated match-detail-page hits between provider syncs.
        return Cache::remember(
            "odds:match:{$match->id}",
            now()->addMinutes(2),
            fn () => Odd::with('market')->where('match_id', $match->id)->get()->groupBy('market.name')
        );
    }

    /**
     * @param array<int, array{selection: string, price: float, external_ref?: string}> $selections
     */
    public function upsertForMatch(GameMatch $match, int $marketId, array $selections, string $provider = 'manual'): void
    {
        foreach ($selections as $selection) {
            Odd::updateOrCreate(
                [
                    'match_id' => $match->id,
                    'market_id' => $marketId,
                    'selection' => $selection['selection'],
                    'provider' => $provider,
                ],
                [
                    'price' => $selection['price'],
                    'external_ref' => $selection['external_ref'] ?? null,
                    'fetched_at' => now(),
                ]
            );
        }

        Cache::forget("odds:match:{$match->id}");
    }
}
