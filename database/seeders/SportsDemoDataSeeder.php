<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\GameMatch;
use App\Models\League;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportsDemoDataSeeder extends Seeder
{
    /**
     * Builds a small but realistic Football dataset — one featured
     * league, eight teams, and a spread of live/today/upcoming/finished
     * matches — so the homepage sections and browse pages have data to
     * render against without needing the external API integration yet.
     */
    public function run(): void
    {
        $football = Sport::where('slug', 'football')->firstOrFail();
        $england = Country::where('slug', 'england')->firstOrFail();

        $league = League::firstOrCreate(
            ['slug' => 'premier-elite-league'],
            [
                'sport_id' => $football->id,
                'country_id' => $england->id,
                'name' => 'Premier Elite League',
                'season' => '2026/2027',
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $teamNames = ['Riverside United', 'Ashford City', 'Northgate Athletic', 'Colton Rovers', 'Fairview FC', 'Highbank United', 'Meadowbrook City', 'Sterling Athletic'];

        $teams = collect($teamNames)->map(fn ($name) => Team::firstOrCreate(
            ['slug' => Str::slug($name)],
            [
                'sport_id' => $football->id,
                'country_id' => $england->id,
                'name' => $name,
                'short_name' => Str::upper(Str::substr(preg_replace('/\s+/', '', $name), 0, 3)),
            ]
        ));

        // 1 live match
        GameMatch::firstOrCreate(
            ['external_provider' => 'demo', 'external_ref' => 'live-1'],
            [
                'sport_id' => $football->id,
                'league_id' => $league->id,
                'home_team_id' => $teams[0]->id,
                'away_team_id' => $teams[1]->id,
                'kickoff_at' => now()->subMinutes(55),
                'status' => GameMatch::STATUS_LIVE,
                'home_score' => 2,
                'away_score' => 1,
                'minute' => 55,
                'is_featured' => true,
            ]
        );

        // 2 more matches today, scheduled later
        GameMatch::firstOrCreate(
            ['external_provider' => 'demo', 'external_ref' => 'today-1'],
            [
                'sport_id' => $football->id,
                'league_id' => $league->id,
                'home_team_id' => $teams[2]->id,
                'away_team_id' => $teams[3]->id,
                'kickoff_at' => now()->setTime(20, 0),
                'status' => GameMatch::STATUS_SCHEDULED,
                'is_featured' => true,
            ]
        );

        GameMatch::firstOrCreate(
            ['external_provider' => 'demo', 'external_ref' => 'today-2'],
            [
                'sport_id' => $football->id,
                'league_id' => $league->id,
                'home_team_id' => $teams[4]->id,
                'away_team_id' => $teams[5]->id,
                'kickoff_at' => now()->setTime(17, 30),
                'status' => GameMatch::STATUS_SCHEDULED,
            ]
        );

        // Upcoming, later in the week
        foreach (range(1, 4) as $i) {
            GameMatch::firstOrCreate(
                ['external_provider' => 'demo', 'external_ref' => "upcoming-{$i}"],
                [
                    'sport_id' => $football->id,
                    'league_id' => $league->id,
                    'home_team_id' => $teams[($i) % 8]->id,
                    'away_team_id' => $teams[($i + 3) % 8]->id,
                    'kickoff_at' => now()->addDays($i)->setTime(19, 0),
                    'status' => GameMatch::STATUS_SCHEDULED,
                    'is_featured' => $i === 1,
                ]
            );
        }

        // A couple of finished matches for league history
        foreach (range(1, 3) as $i) {
            GameMatch::firstOrCreate(
                ['external_provider' => 'demo', 'external_ref' => "finished-{$i}"],
                [
                    'sport_id' => $football->id,
                    'league_id' => $league->id,
                    'home_team_id' => $teams[($i + 1) % 8]->id,
                    'away_team_id' => $teams[($i + 5) % 8]->id,
                    'kickoff_at' => now()->subDays($i)->setTime(19, 0),
                    'status' => GameMatch::STATUS_FINISHED,
                    'home_score' => $i,
                    'away_score' => $i % 2,
                ]
            );
        }
    }
}
