<?php

namespace App\Repositories\Contracts;

use App\Models\GameMatch;
use Illuminate\Support\Collection;

/**
 * Odds are explicitly called out in the brief as "supplied through
 * external LIVE APIs" — this interface is the seam Module 7 uses to
 * swap manually-entered odds for a live provider feed (with caching/
 * retry/rate-limiting inside the concrete implementation) without
 * touching any controller or view that displays odds.
 */
interface OddsRepositoryInterface
{
    public function forMatch(GameMatch $match): Collection;

    public function upsertForMatch(GameMatch $match, int $marketId, array $selections, string $provider = 'manual'): void;
}
