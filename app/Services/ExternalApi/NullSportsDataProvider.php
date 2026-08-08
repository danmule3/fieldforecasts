<?php

namespace App\Services\ExternalApi;

use App\Models\GameMatch;
use App\Models\League;
use App\Services\ExternalApi\Contracts\FixtureProviderInterface;
use App\Services\ExternalApi\Contracts\LiveScoreProviderInterface;
use App\Services\ExternalApi\Contracts\OddsProviderInterface;
use App\Services\ExternalApi\Contracts\StandingsProviderInterface;
use App\Services\ExternalApi\Contracts\StatisticsProviderInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * The "graceful failure" default: bound automatically (see
 * SportsApiServiceProvider) whenever no active API key exists for the
 * configured provider. Every method returns an empty result instead
 * of null/throwing, so sync jobs, controllers, and views built against
 * these interfaces keep working — on manually-entered admin data only
 * — exactly as they did before this module existed, with zero special
 * casing required anywhere else in the app.
 */
class NullSportsDataProvider implements
    FixtureProviderInterface,
    OddsProviderInterface,
    StandingsProviderInterface,
    LiveScoreProviderInterface,
    StatisticsProviderInterface
{
    public function fetchFixtures(League $league, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        return collect();
    }

    public function fetchOdds(GameMatch $match): Collection
    {
        return collect();
    }

    public function fetchStandings(League $league): Collection
    {
        return collect();
    }

    public function fetchLiveScores(): Collection
    {
        return collect();
    }

    public function fetchMatchStatistics(GameMatch $match): ?array
    {
        return null;
    }
}
