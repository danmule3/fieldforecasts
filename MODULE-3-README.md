# Module 3 — Predictions & Odds Engine

Part of **Field Forecast**. Builds on Module 1 (Foundation/Auth/RBAC) and Module 2 (Sports Taxonomy & Match Data).

## What this module delivers

- **`markets`** lookup table seeded with all 13 markets from the brief (1X2, Double Chance, Over 0.5–3.5, BTTS, Correct Score, Draw No Bet, Asian Handicap, Corners, Cards, Player Markets) — a table, not a hardcoded enum, so markets can be added/retired via the admin panel (Module 5) without a migration
- **`odds`** — display-only, provider-agnostic (`external_provider`/`external_ref`), one row per market+selection+match. No code path anywhere accepts a wager; every odds view repeats "informational only" copy
- **`predictions`** — match, market, pick, confidence %, analysis, reasoning, recent form/H2H/injury free-text fields, `is_premium`, and a fast-path `status` (pending/won/lost/cancelled)
- **`prediction_results`** — append-only settlement audit log, deliberately separate from `predictions.status` (see migration comments) so a corrected settlement keeps history instead of silently overwriting the previous outcome
- **`prediction_user`** pivot — powers the "Saved Predictions" dashboard widget reserved since Module 1
- **Events/Listeners:** `PredictionSettled` → `SyncPredictionFastPathStatus` (keeps `predictions.status` in sync — the only writer of that column) and queued `NotifySaversOfSettledPrediction` → `PredictionSettledNotification` (mail + database channels)
- **Repository:** `OddsRepositoryInterface` / `EloquentOddsRepository`, bound alongside `MatchRepositoryInterface` in `RepositoryServiceProvider` — same rationale as Module 2's match repository: the brief explicitly states odds come from an external live API eventually (Module 7), so this is the seam
- **`PredictionService`:** create/update, `settle()` (the only sanctioned way to record an outcome — wraps the result row + event in a transaction), save/unsave toggle, `recentWinners()`, `todaysPredictions()`, `accuracyPercentage()` (cached 10 min)
- **`PredictionPolicy`:** free predictions are publicly viewable; premium ones require `hasActivePremiumAccess()` or staff role. Creating/editing requires Editor+; **settling is restricted to Administrator+** — a deliberate separation of duties so the person who drafts a prediction isn't also the one who grades it
- **Public UI:** predictions index (filterable by sport/free-premium) and detail page with a **blurred preview** for locked premium content (discoverable, not hidden — matches the brief's "Premium Predictions Preview" intent) rather than a hard 403
- **Homepage sections added:** Today's Predictions, Recent Winners, Prediction Accuracy stat
- **Match detail page** (Module 2 placeholder) now shows real predictions and a display-only odds table
- **Dashboard's** Saved Predictions widget is now live

## Folder structure (this module's additions)

```
app/
  Models/ (Market, Odd, Prediction, PredictionResult)
  Events/PredictionSettled.php
  Listeners/ (SyncPredictionFastPathStatus, NotifySaversOfSettledPrediction)
  Notifications/PredictionSettledNotification.php
  Repositories/
    Contracts/OddsRepositoryInterface.php
    Eloquent/EloquentOddsRepository.php
  Services/PredictionService.php
  Policies/PredictionPolicy.php
  Http/
    Controllers/ (PredictionController, SavedPredictionController)
    Requests/ (StorePredictionRequest, UpdatePredictionRequest, SettlePredictionRequest)
database/
  migrations/ (markets, odds, predictions, prediction_results, prediction_user, notifications)
  factories/ (Market, Odd, Prediction)
  seeders/ (MarketSeeder, PredictionsDemoSeeder)
resources/views/
  predictions/ (index, show)
  components/prediction-card.blade.php
routes/predictions.php
tests/Feature/PredictionTest.php
```

## Setup additions

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\MarketSeeder
php artisan db:seed --class=Database\\Seeders\\PredictionsDemoSeeder
# or just: php artisan migrate:fresh --seed
```

Register `App\Providers\EventServiceProvider` in `bootstrap/providers.php` alongside the others. Confirm your queue worker is running (`php artisan queue:work`) so `NotifySaversOfSettledPrediction` actually processes — it's `ShouldQueue` on purpose per the brief's "Use Queues" requirement, since a popular prediction could have thousands of savers.

## Design notes

- **Why `prediction_results` AND `predictions.status`:** the brief lists "Prediction Results" as its own database table under the Database section, distinct from the Won/Lost/Pending/Cancelled status field listed under Predictions. Treating the results table as an audit log (who settled it, when, with what notes) rather than a duplicate of the status column gives real value — corrections are traceable — instead of just satisfying the letter of the spec.
- **Why settling is Admin+, not Editor:** the brief doesn't specify this, but "Editor" drafting predictions and a higher role confirming outcomes is a standard editorial-integrity pattern worth having in a platform whose entire credibility rests on its stated accuracy %. Worth revisiting if your actual editorial workflow differs — it's one line in `PredictionPolicy::settle()` to change.
- **Blur-preview vs 403 for premium content:** chose to render the shell (teams, market, confidence number) but blur/withhold the analysis text, both on cards and the detail page. This matches typical "Premium Predictions Preview" UX and gives non-subscribers a reason to convert, rather than hiding the prediction's existence entirely.
- **Odds caching:** 2-minute TTL per match, invalidated on `upsertForMatch()` — short enough to reflect line movement, long enough to absorb repeated page hits on a popular fixture.

## What's intentionally deferred

- Admin panel screens for Manage Predictions/Odds/Markets (the Store/Update/Settle request classes, service methods, and policy are already built — Module 5 just needs to add the controller actions + Blade CRUD screens using them)
- Subscriptions/billing and the real `is_premium` sync (currently a manual boolean on `users`, set directly — Module 4 replaces this with actual subscription lifecycle management)
- External Odds API integration replacing `provider = 'manual'` rows — Module 7
- Weather integration for match/prediction context — explicitly listed in the brief as "future integration," no seam built for it yet

## Next module

**Module 4 — Premium Subscriptions**: `subscription_plans` (Weekly/Monthly, no annual), `subscriptions` (history, expiry, auto access control), billing history, renew reminders (scheduled task + notification), subscription dashboard, and wiring `is_premium`/`premium_expires_at` on `users` to a real subscription lifecycle instead of the placeholder boolean. Say "continue" when ready.
