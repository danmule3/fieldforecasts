<?php

namespace App\Services\ExternalApi\Contracts;

use App\Models\GameMatch;

interface StatisticsProviderInterface
{
    /**
     * Returns the raw stats payload for a match (possession, shots,
     * corners, cards, etc.) as an associative array, stored verbatim
     * into `matches.statistics` (JSON) — see that column's migration
     * comment for why this isn't a rigid per-stat schema. Returns null
     * if the provider has no statistics for this match (e.g. not yet
     * kicked off).
     */
    public function fetchMatchStatistics(GameMatch $match): ?array;
}
