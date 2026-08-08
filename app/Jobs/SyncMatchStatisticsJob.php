<?php

namespace App\Jobs;

use App\Models\GameMatch;
use App\Services\ExternalApi\Contracts\StatisticsProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMatchStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(private readonly GameMatch $match)
    {
    }

    public function handle(StatisticsProviderInterface $provider): void
    {
        if (! $this->match->external_ref) {
            return;
        }

        $stats = $provider->fetchMatchStatistics($this->match);

        if ($stats !== null) {
            $this->match->update(['statistics' => $stats]);
        }
    }
}
