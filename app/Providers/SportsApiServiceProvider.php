<?php

namespace App\Providers;

use App\Services\ApiKeyService;
use App\Services\ExternalApi\Contracts\FixtureProviderInterface;
use App\Services\ExternalApi\Contracts\LiveScoreProviderInterface;
use App\Services\ExternalApi\Contracts\OddsProviderInterface;
use App\Services\ExternalApi\Contracts\StandingsProviderInterface;
use App\Services\ExternalApi\Contracts\StatisticsProviderInterface;
use App\Services\ExternalApi\NullSportsDataProvider;
use App\Services\ExternalApi\SportSrcProvider;
use Illuminate\Support\ServiceProvider;

class SportsApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach ([
            FixtureProviderInterface::class,
            OddsProviderInterface::class,
            StandingsProviderInterface::class,
            LiveScoreProviderInterface::class,
            StatisticsProviderInterface::class,
        ] as $contract) {
            $this->app->bind($contract, function ($app) {
                $providerKey = config('sports_api.default');

                // Resolved per-request (not cached at boot) so activating
                // an API key in the admin panel takes effect immediately,
                // without a deploy or container restart.
                if ($app->make(ApiKeyService::class)->hasActiveKey($providerKey)) {
                    return $app->make(SportSrcProvider::class);
                }

                return $app->make(NullSportsDataProvider::class);
            });
        }
    }
}
