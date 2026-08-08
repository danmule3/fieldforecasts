<?php

namespace App\Services\ExternalApi\Contracts;

use App\Models\GameMatch;
use Illuminate\Support\Collection;

interface OddsProviderInterface
{
    /**
     * Returns a Collection grouped by market key, each entry a list of
     * ['selection' => ..., 'price' => ..., 'external_ref' => ...]
     * — shaped to match what OddsRepositoryInterface::upsertForMatch()
     * (Module 3) already expects, so the sync job is a thin adapter
     * between the two, not new merge logic.
     */
    public function fetchOdds(GameMatch $match): Collection;
}
