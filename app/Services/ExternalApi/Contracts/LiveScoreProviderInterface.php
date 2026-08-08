<?php

namespace App\Services\ExternalApi\Contracts;

use Illuminate\Support\Collection;

interface LiveScoreProviderInterface
{
    /**
     * Returns a Collection of ['external_ref', 'home_score',
     * 'away_score', 'minute', 'status'] for every fixture the provider
     * currently reports as in-progress, across all tracked leagues —
     * a single call rather than one per match, since that's how live
     * score APIs are typically shaped (one feed, filtered client-side).
     */
    public function fetchLiveScores(): Collection;
}
