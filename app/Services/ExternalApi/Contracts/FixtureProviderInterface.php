<?php

namespace App\Services\ExternalApi\Contracts;

use App\Models\League;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface FixtureProviderInterface
{
    /**
     * Returns a Collection of associative arrays, one per fixture:
     * ['external_ref', 'home_team_external_ref', 'home_team_name',
     *  'away_team_external_ref', 'away_team_name', 'kickoff_at', 'venue']
     * — deliberately a plain array shape, not a rigid DTO class, since
     * different providers expose different subsets of fields and
     * SyncLeagueFixturesJob only requires these keys to exist.
     */
    public function fetchFixtures(League $league, ?Carbon $from = null, ?Carbon $to = null): Collection;
}
