# Module 7 — External Live API Integration

Part of **Field Forecast**. Builds on Modules 1–6 — this is where the `external_provider`/`external_ref` columns quietly added to leagues, teams, matches, and odds since Module 2 finally get used.

## What this module delivers

- **Five provider interfaces** (`FixtureProviderInterface`, `OddsProviderInterface`, `StandingsProviderInterface`, `LiveScoreProviderInterface`, `StatisticsProviderInterface`) matching the brief's five named APIs — kept as separate contracts (not one combined interface) since a real deployment might use different vendors for different data
- **`AbstractApiProvider`** — the shared HTTP mechanics every concrete provider inherits: API-key auth header, timeout, retry (`Http::retry()`), per-provider rate limiting (`RateLimiter`), response caching, and graceful failure (every failure mode — bad response, timeout, exhausted retries, rate limit hit, missing key — logs and returns `null`/empty rather than throwing)
- **`SportsDataApiProvider`** — concrete adapter implementing all five interfaces, with endpoint paths and response mapping clearly marked as illustrative placeholders to adapt to whichever real vendor gets contracted
- **`NullSportsDataProvider`** — the "graceful failure" default. Bound automatically whenever no active API key exists, so the entire platform keeps running on manually-entered admin data (exactly as it has since Module 2) with zero special-casing anywhere else in the app
- **`SportsApiServiceProvider`** — resolves each interface to the real or Null provider *per request*, based on whether an active `ApiKey` exists — activating a key in the admin panel takes effect immediately, no deploy needed
- **Queue jobs**: `SyncLeagueFixturesJob`, `SyncMatchOddsJob`, `SyncLeagueStandingsJob`, `SyncLiveScoresJob`, `SyncMatchStatisticsJob` — all idempotent upserts keyed on `external_provider`+`external_ref`, all `ShouldQueue` per the brief's "Queue processing" requirement
- **`sports-api:sync {type}`** console command + schedule entries (fixtures daily, standings hourly, odds every 15 min, live scores every minute) — every one a safe no-op until a key is configured
- **`standings` table + model** — the brief calls out a Standings API; this needed a real table, not just a display field. Public league pages now show a standings table; Editors can also enter rows manually (same "manual fallback alongside the sync job" pattern as Module 3's odds)
- **`matches.statistics` JSON column** — raw stats blob from the Statistics API, rendered on the match detail page when present
- **Manage API Keys** — Administrator+ only (not Editor — credentials are infrastructure, not editorial content), encrypted at rest, masked to last 4 characters in the UI, never logged

## Folder structure (this module's additions)

```
app/
  Services/
    ApiKeyService.php
    ExternalApi/
      Contracts/ (Fixture, Odds, Standings, LiveScore, Statistics)ProviderInterface.php
      AbstractApiProvider.php
      SportsDataApiProvider.php
      NullSportsDataProvider.php
  Jobs/ (SyncLeagueFixturesJob, SyncMatchOddsJob, SyncLeagueStandingsJob,
    SyncLiveScoresJob, SyncMatchStatisticsJob)
  Console/Commands/SyncSportsData.php
  Providers/SportsApiServiceProvider.php
  Models/ (ApiKey, Standing)
  Http/Controllers/Admin/ (ApiKeyController, StandingController)
config/sports_api.php
database/
  migrations/ (api_keys, standings, matches.statistics column)
  factories/StandingFactory.php
resources/views/admin/(api-keys, standings)
tests/Feature/ExternalApiTest.php
```

## Setup additions

```bash
php artisan migrate
```

Register `App\Providers\SportsApiServiceProvider` in `bootstrap/providers.php` alongside the others. Set up the queue worker and scheduler as usual (`php artisan queue:work`, cron → `schedule:run`) — both already required since Module 4.

To connect a real provider: add an API key in Admin → API Keys, and update `config/sports_api.php` (`base_url` etc. — all env-driven, no code change) plus `SportsDataApiProvider`'s endpoint paths/field mapping to match that vendor's actual API shape. Map each `League`/`Team` you want synced to that vendor's ID via the `external_ref` field (Admin → Leagues/Teams edit forms — already existed since Module 2, just unused until now).

## Design notes

- **Why the Null provider pattern instead of a `null`-checking `if` in every consumer:** every controller, job, and view that depends on these five interfaces was written in Modules 2–3 with zero awareness that an external API might or might not be configured. That's exactly the point — the Null Object pattern means this module bolts on without touching any of that code. `SyncLeagueFixturesJob::handle()` doesn't check "is there a provider configured"; it just calls `$provider->fetchFixtures()` and gets an empty Collection back if not.
- **Why sync jobs upsert rather than replace:** a match that already has manually-entered predictions and odds (Module 3) must never be deleted and recreated by a fixture sync — `updateOrCreate` on `external_provider`+`external_ref` means the same local row just gets its kickoff time/venue refreshed.
- **Why Standings needed a real table, not just a `Setting` blob:** it's genuinely relational data (per-team, per-league, per-season) that both the sync job and manual Editor entry need to write to identically — reusing the exact same table both paths update is what keeps them from drifting apart.
- **Why `matches.statistics` is a JSON blob, not a fixed schema:** see the migration's own comment — football and cricket statistics have almost nothing in common, and this column is always read as a unit (rendered as a definition list), never filtered by individual stat value.

## What's intentionally deferred

- A real vendor contract/credentials — `SportsDataApiProvider` is a template, not a finished integration
- Webhook-based push updates (currently polling only, via the schedule) — most fixture/odds providers support both; polling was chosen as the simpler default
- Per-provider circuit breaker (currently rate-limiting and retry, but a sustained outage still gets hit on every scheduled tick — a "provider looks down, back off longer" state machine would need `ApiKey` to track consecutive-failure counts)
- Statistics displayed with a raw `Str::headline()`'d key rather than a curated, sport-aware layout

## Next module

**Module 8 — SEO, Performance & Production Hardening**: sitewide meta tags/schema.org (SportsEvent, FAQ schema), XML sitemap, Open Graph/Twitter Cards, canonical URLs, robots.txt, database indexing review, image optimization/lazy loading, AI-search/LLM optimization (AEO). Say "continue" when ready — this closes out the deliverables list from the original brief.
