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
 * Concrete adapter for SportSRC v2 (https://sportsrc.org/v2/).
 *
 * Shape of this API, unlike a typical path-based REST API: ONE base
 * URL, every resource selected via a `type=` query parameter
 * (type=matches, type=detail, type=odds, type=standing, type=stats,
 * etc.) rather than `/leagues/{id}/fixtures`-style paths. `request()`
 * is therefore always called with an empty endpoint and the `type`
 * folded into the query array.
 *
 * IMPORTANT — response field mapping below is a first-pass best
 * effort: SportSRC's public docs describe the request parameters for
 * each `type`, but not example response bodies, so the exact JSON
 * field names (`home_team` vs `homeTeam` vs nested under `teams`,
 * etc.) are not yet confirmed against a real payload. Every `map*()`
 * method below is written defensively (falls back to null/empty
 * rather than erroring on a missing key) so a wrong guess degrades
 * gracefully instead of crashing a sync job — but treat the exact
 * field names as provisional until verified with
 * `php artisan sports-api:test-fetch matches` against a live response
 * and adjusted here.
 *
 * Also note: the `matches` endpoint (used for fetchFixtures) takes
 * `sport`/`status`/`date` filters but no per-league ID filter in the
 * documented parameters — there's no confirmed way to ask SportSRC
 * for "just Premier Elite League's fixtures." fetchFixtures() below
 * pulls by sport+date and relies on matching returned data against
 * the league's `external_ref`/name where present in the response;
 * if SportSRC's `matches` payload turns out not to include a league
 * identifier at all, this will need revisiting once we see a real
 * response (again: run test-fetch and share the output).
 */
class SportSrcProvider extends AbstractApiProvider implements
    FixtureProviderInterface,
    OddsProviderInterface,
    StandingsProviderInterface,
    LiveScoreProviderInterface,
    StatisticsProviderInterface
{
    protected string $providerKey = 'sportsrc';

    public function fetchFixtures(League $league, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $data = $this->request('', [
            'type' => 'matches',
            'sport' => 'football',
            'date' => ($from ?? now())->toDateString(),
        ]);

        return collect($data['matches'] ?? $data['data'] ?? [])
            ->filter(fn ($m) => $this->matchBelongsToLeague($m, $league))
            ->map(fn ($m) => [
                'external_ref' => (string) ($m['id'] ?? $m['match_id'] ?? ''),
                'home_team_external_ref' => (string) ($m['home_team']['id'] ?? $m['home']['id'] ?? ''),
                'home_team_name' => $m['home_team']['name'] ?? $m['home']['name'] ?? '',
                'away_team_external_ref' => (string) ($m['away_team']['id'] ?? $m['away']['id'] ?? ''),
                'away_team_name' => $m['away_team']['name'] ?? $m['away']['name'] ?? '',
                'kickoff_at' => $m['kickoff_at'] ?? $m['start_time'] ?? $m['date'] ?? null,
                'venue' => $m['venue'] ?? null,
            ])
            ->filter(fn ($f) => $f['external_ref'] !== '');
    }

    public function fetchOdds(GameMatch $match): Collection
    {
        // Deep Data tier (odds/stats/standings/etc.) requires a paid
        // SportSRC plan — on the free tier this call will fail and
        // AbstractApiProvider::request() returns null, so this
        // correctly yields an empty Collection rather than an error.
        $data = $this->request('', ['type' => 'odds', 'id' => $match->external_ref]);

        return collect($data['markets'] ?? $data['odds'] ?? [])->mapWithKeys(fn ($market) => [
            ($market['key'] ?? $market['market'] ?? 'unknown') => collect($market['selections'] ?? $market['outcomes'] ?? [])->map(fn ($s) => [
                'selection' => $s['name'] ?? $s['selection'] ?? '',
                'price' => (float) ($s['price'] ?? $s['odds'] ?? $s['decimal'] ?? 0),
                'external_ref' => (string) ($s['id'] ?? ''),
            ]),
        ]);
    }

    public function fetchStandings(League $league): Collection
    {
        $data = $this->request('', ['type' => 'standing', 'league_id' => $league->external_ref]);

        return collect($data['standings'] ?? $data['table'] ?? [])->map(fn ($row) => [
            'team_external_ref' => (string) ($row['team']['id'] ?? $row['team_id'] ?? ''),
            'team_name' => $row['team']['name'] ?? $row['team_name'] ?? '',
            'position' => (int) ($row['position'] ?? $row['rank'] ?? 0),
            'played' => (int) ($row['played'] ?? $row['matches_played'] ?? 0),
            'won' => (int) ($row['won'] ?? $row['wins'] ?? 0),
            'drawn' => (int) ($row['drawn'] ?? $row['draws'] ?? 0),
            'lost' => (int) ($row['lost'] ?? $row['losses'] ?? 0),
            'goals_for' => (int) ($row['goals_for'] ?? $row['gf'] ?? 0),
            'goals_against' => (int) ($row['goals_against'] ?? $row['ga'] ?? 0),
            'points' => (int) ($row['points'] ?? $row['pts'] ?? 0),
        ])->filter(fn ($r) => $r['team_external_ref'] !== '');
    }

    public function fetchLiveScores(): Collection
    {
        $data = $this->request('', [
            'type' => 'matches',
            'sport' => 'football',
            'status' => 'inprogress',
        ]);

        return collect($data['matches'] ?? $data['data'] ?? [])->map(fn ($m) => [
            'external_ref' => (string) ($m['id'] ?? $m['match_id'] ?? ''),
            'home_score' => $m['home_score'] ?? $m['score']['home'] ?? null,
            'away_score' => $m['away_score'] ?? $m['score']['away'] ?? null,
            'minute' => $m['minute'] ?? $m['clock'] ?? null,
            'status' => $this->mapStatus($m['status'] ?? null),
        ])->filter(fn ($f) => $f['external_ref'] !== '');
    }

    public function fetchMatchStatistics(GameMatch $match): ?array
    {
        // Deep Data tier — see fetchOdds() note above.
        return $this->request('', ['type' => 'stats', 'id' => $match->external_ref]);
    }

    /**
     * Best-effort league match on whatever the `matches` response
     * includes — checked in order of specificity. If SportSRC's
     * response includes neither a league ID nor a league name field,
     * this always returns true (no filtering), which is safer than
     * silently dropping every fixture; revisit once a real response
     * is seen.
     */
    private function matchBelongsToLeague(array $matchData, League $league): bool
    {
        $leagueRef = $matchData['league']['id'] ?? $matchData['league_id'] ?? null;
        if ($leagueRef !== null && $league->external_ref) {
            return (string) $leagueRef === (string) $league->external_ref;
        }

        $leagueName = $matchData['league']['name'] ?? $matchData['competition'] ?? null;
        if ($leagueName !== null) {
            return str($leagueName)->lower()->contains(str($league->name)->lower());
        }

        return true;
    }

    /** Vendor status strings aren't confirmed either — normalize what's plausible, default to scheduled. */
    private function mapStatus(?string $providerStatus): string
    {
        return match (strtolower((string) $providerStatus)) {
            'inprogress', 'live', 'in_play' => GameMatch::STATUS_LIVE,
            'finished', 'ft', 'ended', 'completed' => GameMatch::STATUS_FINISHED,
            'postponed' => GameMatch::STATUS_POSTPONED,
            'cancelled', 'canceled' => GameMatch::STATUS_CANCELLED,
            default => GameMatch::STATUS_SCHEDULED,
        };
    }
}
