<?php

namespace App\Jobs;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use App\Services\ExternalApi\Contracts\FixtureProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Idempotent upsert keyed on (external_provider, external_ref) — the
 * same job can run every day without creating duplicate fixtures, and
 * a fixture that already has manually-entered predictions/odds
 * attached (Module 3) is safely updated in place, never replaced.
 */
class SyncLeagueFixturesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(private readonly League $league)
    {
    }

    public function handle(FixtureProviderInterface $provider): void
    {
        if (! $this->league->external_ref) {
            return; // league isn't mapped to a provider ID — nothing to sync
        }

        $fixtures = $provider->fetchFixtures($this->league, now(), now()->addDays(14));

        foreach ($fixtures as $fixture) {
            $homeTeam = $this->resolveTeam($fixture['home_team_external_ref'], $fixture['home_team_name']);
            $awayTeam = $this->resolveTeam($fixture['away_team_external_ref'], $fixture['away_team_name']);

            if (! $homeTeam || ! $awayTeam || ! $fixture['kickoff_at']) {
                continue;
            }

            GameMatch::updateOrCreate(
                ['external_provider' => 'sportsrc', 'external_ref' => $fixture['external_ref']],
                [
                    'sport_id' => $this->league->sport_id,
                    'league_id' => $this->league->id,
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'kickoff_at' => Carbon::parse($fixture['kickoff_at']),
                    'venue' => $fixture['venue'] ?? null,
                ]
            );
        }
    }

    private function resolveTeam(?string $externalRef, string $name): ?Team
    {
        if (! $externalRef || ! $name) {
            return null;
        }

        return Team::firstOrCreate(
            ['external_provider' => 'sportsrc', 'external_ref' => $externalRef],
            [
                'sport_id' => $this->league->sport_id,
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name) . '-' . \Illuminate\Support\Str::random(5),
            ]
        );
    }
}
