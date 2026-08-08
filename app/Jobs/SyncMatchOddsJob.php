<?php

namespace App\Jobs;

use App\Models\GameMatch;
use App\Models\Market;
use App\Repositories\Contracts\OddsRepositoryInterface;
use App\Services\ExternalApi\Contracts\OddsProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMatchOddsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(private readonly GameMatch $match)
    {
    }

    public function handle(OddsProviderInterface $provider, OddsRepositoryInterface $oddsRepository): void
    {
        if (! $this->match->external_ref) {
            return;
        }

        $byMarketKey = $provider->fetchOdds($this->match);

        foreach ($byMarketKey as $marketKey => $selections) {
            $market = Market::where('key', $marketKey)->first();

            if (! $market || $selections->isEmpty()) {
                continue;
            }

            // Reuses the exact repository method Module 3 built for the
            // admin odds screen — sync and manual entry share one write path.
            $oddsRepository->upsertForMatch($this->match, $market->id, $selections->all(), 'sportsrc');
        }
    }
}
