<?php

namespace App\Console\Commands;

use App\Jobs\SyncLeagueFixturesJob;
use App\Jobs\SyncLeagueStandingsJob;
use App\Jobs\SyncLiveScoresJob;
use App\Jobs\SyncMatchOddsJob;
use App\Models\GameMatch;
use App\Models\League;
use App\Services\ApiKeyService;
use Illuminate\Console\Command;

class SyncSportsData extends Command
{
    protected $signature = 'sports-api:sync {type : fixtures|odds|standings|live-scores}';

    protected $description = 'Dispatch queue jobs to sync fixtures, odds, standings, or live scores from the configured external sports-data provider.';

    public function handle(ApiKeyService $apiKeys): int
    {
        $provider = config('sports_api.default');

        if (! $apiKeys->hasActiveKey($provider)) {
            $this->info("No active API key for [{$provider}] — nothing to sync (this is expected until one is added in Admin > API Keys).");
            return self::SUCCESS;
        }

        $mappedLeagues = League::where('is_active', true)->whereNotNull('external_ref')->get();

        match ($this->argument('type')) {
            'fixtures' => $mappedLeagues->each(fn (League $league) => SyncLeagueFixturesJob::dispatch($league)),
            'standings' => $mappedLeagues->each(fn (League $league) => SyncLeagueStandingsJob::dispatch($league)),
            'odds' => GameMatch::where('external_provider', 'sportsrc')
                ->upcoming()
                ->get()
                ->each(fn (GameMatch $match) => SyncMatchOddsJob::dispatch($match)),
            'live-scores' => SyncLiveScoresJob::dispatch(),
            default => $this->error('Unknown sync type. Use: fixtures, odds, standings, or live-scores.'),
        };

        $this->info("Dispatched {$this->argument('type')} sync job(s) for provider [{$provider}].");

        return self::SUCCESS;
    }
}
