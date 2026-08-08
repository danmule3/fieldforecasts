<?php

namespace App\Services\ExternalApi\Contracts;

use App\Models\League;
use Illuminate\Support\Collection;

interface StandingsProviderInterface
{
    /**
     * Returns a Collection of associative arrays, one per team:
     * ['team_external_ref', 'team_name', 'position', 'played', 'won',
     *  'drawn', 'lost', 'goals_for', 'goals_against', 'points']
     */
    public function fetchStandings(League $league): Collection;
}
