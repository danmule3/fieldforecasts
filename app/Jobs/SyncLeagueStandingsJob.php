<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Standing;
use App\Models\Team;
use App\Services\ExternalApi\Contracts\StandingsProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncLeagueStandingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(private readonly League $league)
    {
    }

    public function handle(StandingsProviderInterface $provider): void
    {
        if (! $this->league->external_ref) {
            return;
        }

        $rows = $provider->fetchStandings($this->league);

        foreach ($rows as $row) {
            $team = Team::where('external_provider', 'sportsrc')
                ->where('external_ref', $row['team_external_ref'])
                ->first();

            if (! $team) {
                continue; // team must already exist via fixture sync — standings alone can't safely create one
            }

            Standing::updateOrCreate(
                ['league_id' => $this->league->id, 'team_id' => $team->id, 'season' => $this->league->season],
                [
                    'position' => $row['position'],
                    'played' => $row['played'],
                    'won' => $row['won'],
                    'drawn' => $row['drawn'],
                    'lost' => $row['lost'],
                    'goals_for' => $row['goals_for'],
                    'goals_against' => $row['goals_against'],
                    'points' => $row['points'],
                    'external_provider' => 'sportsrc',
                    'external_ref' => $row['team_external_ref'],
                ]
            );
        }
    }
}
