# Module 2 — Sports Taxonomy & Match Data

Part of **Field Forecast**. Builds on Module 1 (Foundation/Auth/RBAC).

## What this module delivers

- Migrations/models for **Sports, Countries, Leagues, Teams, Matches** — all provider-agnostic (`external_provider` + `external_ref` columns) so Module 7's fixture API integration can populate/sync these tables without a schema change
- `GameMatch` model (table `matches`; named to avoid clashing with PHP's `match` keyword) with `live()`, `upcoming()`, `today()` query scopes and a status enum (`scheduled`, `live`, `finished`, `postponed`, `cancelled`)
- **Repository pattern** introduced here specifically: `MatchRepositoryInterface` + `EloquentMatchRepository`, bound in `RepositoryServiceProvider` — this is the seam Module 7 uses to layer a live external API on top of (or instead of) local data, without touching controllers/views
- `MatchService` — homepage section aggregation (live/today/upcoming/featured), league/browse pagination, all with short-TTL caching on the volatile reads (30s for live, 5min for today)
- Public browsing: sport category pages, league pages, team pages (with follow/unfollow), match detail page, filterable match index (`MatchFilterRequest` validates `sport`/`league_id`/`status`/`date` query params)
- Homepage now renders real **Live Matches, Today's Predictions (matches), Featured, Upcoming** sections instead of the Module 1 placeholder
- Dashboard's "Favourite Teams" widget is now wired to real data (`team_user` pivot table)
- Seeders: `SportSeeder` (7 sports), `CountrySeeder` (8 countries), `SportsDemoDataSeeder` (1 league, 8 teams, mix of live/today/upcoming/finished matches) — enough demo data to see every homepage section populated
- Feature tests covering homepage rendering, sport filtering, league/team pages, and follow/unfollow

## Folder structure (this module's additions)

```
app/
  Models/ (Sport, Country, League, Team, GameMatch)
  Repositories/
    Contracts/MatchRepositoryInterface.php
    Eloquent/EloquentMatchRepository.php
  Services/MatchService.php
  Providers/RepositoryServiceProvider.php
  Http/
    Controllers/ (HomeController, SportController, LeagueController, TeamController, MatchController)
    Requests/MatchFilterRequest.php
database/
  migrations/ (sports taxonomy, matches + team_user pivot)
  factories/ (Sport, Country, League, Team, GameMatch)
  seeders/ (Sport, Country, SportsDemoData)
resources/views/
  sports/ (index, show)
  leagues/show.blade.php
  teams/show.blade.php
  matches/ (index, show)
  components/ (match-card, match-section)
routes/sports.php
tests/Feature/MatchBrowsingTest.php
```

## Setup additions

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\SportSeeder
php artisan db:seed --class=Database\\Seeders\\CountrySeeder
php artisan db:seed --class=Database\\Seeders\\SportsDemoDataSeeder
# or just: php artisan migrate:fresh --seed  (runs all seeders via DatabaseSeeder)
```

Register `App\Providers\RepositoryServiceProvider` in `bootstrap/providers.php` alongside `AuthServiceProvider`.

## Design notes

- **Why a repository here and nowhere else yet:** per the brief's "use Repository Pattern where appropriate" — match data is the one entity in this module whose source of truth will change (local DB now, external live API later per Module 7's spec). Sports/Countries/Leagues/Teams stay plain Eloquent because they don't have that same swap-in-a-provider requirement; introducing repositories for all five would be needless indirection.
- **Caching:** live matches cache 30s, today's matches cache 5min, both keyed by sport so per-category pages don't invalidate each other. This is a placeholder cache store (works with `file`/`array`/`redis` interchangeably per your `CACHE_STORE` env) — swap to Redis in production per the brief's "Redis Ready" requirement, no code change needed.
- **Route model binding by slug:** `Sport`, `League`, `Team` all override `getRouteKeyName()` to `slug` — URLs are `/sports/football`, `/leagues/premier-elite-league`, `/teams/riverside-united`, not numeric IDs, which matters for the SEO-friendly-URL requirement (full SEO layer lands in Module 8).

## What's intentionally deferred

- Predictions, Odds, Prediction Results, confidence %, analysis/reasoning, H2H, injuries — **Module 3** (the match detail page has a placeholder panel exactly where this content will render)
- Admin CRUD for all of these entities (Manage Sports/Countries/Leagues/Teams/Matches) — **Module 5**
- External Fixture/Odds/Scores API service layer — **Module 7** (this module's repository interface is the integration seam)
- Standings/league tables — out of original scope list but a natural `LeagueStanding` addition alongside Module 3 if you want it

## Next module

**Module 3 — Predictions & Odds Engine**: `predictions`, `odds`, `prediction_results` tables; confidence %, analysis/reasoning fields; supported markets (1X2, BTTS, Over/Under, Asian Handicap, etc.); free vs premium gating using the `view-premium-content` gate from Module 1; "Recent Winners" and "Prediction Accuracy" homepage sections. Say "continue" when ready.
