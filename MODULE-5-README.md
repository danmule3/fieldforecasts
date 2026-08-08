# Module 5 — Admin Panel

Part of **Field Forecast**. Builds on Modules 1–4 — this module is primarily wiring, not new domain logic: almost every controller here calls into a Service/Policy/Request already built in an earlier module.

## What this module delivers

- **Access control:** entire `/admin` route group gated by `auth` + `verified` + `can:access-admin-panel` middleware (the gate from Module 1). Individual actions layer on finer-grained checks — `UserPolicy`, `PredictionPolicy`, or a hard role check (`RoleController`) — rather than one blanket permission
- **Dashboard:** registered/premium user counts, revenue, active subscriptions, subscription growth (this month vs last), prediction totals, most-viewed predictions, popular sports, recent activity feed — all via the new `AnalyticsService`
- **Manage Sports, Countries, Markets, Leagues, Teams, Matches** — full CRUD, Editor+ access
- **Manage Odds** — per-match odds board (not a standalone flat list — odds are meaningless without match/market context), built directly on the Module 3 `OddsRepositoryInterface`
- **Manage Predictions** — create/edit/delete reuse Module 3's `StorePredictionRequest`/`UpdatePredictionRequest`/`PredictionService` verbatim; **settling** reuses `SettlePredictionRequest` and is enforced Administrator+ only via the existing `PredictionPolicy::settle()`
- **Manage Users** — list/search, role change (Super Admin only, self-promotion blocked — `UserPolicy::changeRole`), suspend/reactivate, delete — all through `UserPolicy`, all writing to `activity_logs` via `ActivityLogger`
- **Manage Roles & Permissions** — Super Admin only (hard-checked in the controller, not just gated — see design notes); Super Administrator's own permission set is intentionally not editable, since it's granted unconditionally via `Gate::before` regardless of what's synced to the role row
- **Manage Subscription Plans** — CRUD; "delete" soft-disables (`is_active = false`) rather than hard-deleting, since `subscriptions.subscription_plan_id` is `restrictOnDelete` and existing subscribers' history must stay intact
- **Manage Payments** — read-only ledger (refunds need a real gateway's refund API — deferred alongside the rest of live payment integration)
- **Reports/Logs** — browsable, filterable view over `activity_logs`
- **Settings** — key-value site settings (`SettingsService`, cached), editable from the panel instead of requiring a deploy

## Folder structure (this module's additions)

```
app/
  Http/Controllers/Admin/ (AdminController base, AdminDashboardController,
    SportController, CountryController, MarketController, LeagueController,
    TeamController, MatchController, OddController, PredictionController,
    UserController, RoleController, SubscriptionPlanController,
    PaymentController, ActivityLogController, SettingsController)
  Http/Controllers/Controller.php  (base class — see Module 4 note; still holds)
  Models/Setting.php
  Services/ (AnalyticsService, SettingsService)
database/
  migrations/ (predictions.views_count, settings)
  seeders/SettingsSeeder.php
resources/views/
  layouts/admin.blade.php
  components/admin/sidebar.blade.php
  admin/ (dashboard, sports, countries, markets, leagues, teams, matches,
    odds, predictions, users, roles, subscription-plans, payments, logs, settings)
routes/admin.php
tests/Feature/AdminPanelTest.php
```

## Setup additions

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\SettingsSeeder
```

No new provider registration needed — everything here reuses bindings/policies registered in Modules 1–4.

## Design notes

- **Why `AdminController` doesn't call `$this->middleware()`:** Laravel 11+ moved away from registering middleware inside controller constructors in favor of route-level middleware. The `access-admin-panel` gate is applied once, to the whole `routes/admin.php` group, rather than repeated per controller — one line to audit instead of fifteen.
- **Why Roles & Permissions is a hard role check, not a Policy:** every other admin area is reachable by whichever role has the right Gate/Policy outcome, and that's fine — permissions can evolve. Role management is different: if a bug or a future permission sync ever accidentally granted `roles.manage` to a non-Super-Admin, that would be a privilege-escalation hole with no ceiling. Hard-coding the role check in `RoleController::__construct()` means changing who can manage roles requires touching this file directly, not adjusting a permissions table row.
- **"Daily Visitors" is deliberately absent from the Analytics section.** Every other stat in the brief's Analytics list is derivable from data this platform already owns (users, predictions, payments, subscriptions). Visitor counts need real traffic instrumentation — faking a number here would be worse than not showing the widget.
- **Odds admin UX caps at 3 selection/price rows per submission** (covers 1X2 and most binary markets cleanly). Markets needing more selections (Correct Score, Player Markets) will want a dynamic add-row Alpine.js UI — noted as a follow-up rather than over-building this release.

## What's intentionally deferred

- Manage Articles/FAQ/Testimonials/Advertisements/Homepage/Menus/Banners/Sliders/Newsletter/Pages — these are all content-management surfaces for the Blog/CMS, not the sports-data domain this module covers → **Module 6**
- Manage API Keys — depends on the external Fixture/Odds/Scores API integration existing → **Module 7**
- Payment refunds — needs a live gateway's refund endpoint
- Dynamic multi-row odds entry UI
- Bulk actions (bulk-settle predictions, bulk user role changes) — not in the original brief, easy addition later if needed

## Next module

**Module 6 — Blog/CMS**: Articles (categories, authors, tags, featured images, SEO fields, comments), FAQ, Testimonials, Advertisements, Homepage/Menu/Banner/Slider management, Newsletter — plus the corresponding Manage screens in the admin panel. Say "continue" when ready.
