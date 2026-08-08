# Field Forecast

A football and sports prediction platform — predictions, odds information, statistics, and analysis only. **Field Forecast does not accept wagers or facilitate betting of any kind.**

Built on Laravel 12 (Blade + Alpine.js + Tailwind CSS, no JS framework), across 8 modules. See each module's README for what it delivers, the design rationale behind it, and what's intentionally deferred:

1. [MODULE-1-README.md](MODULE-1-README.md) — Foundation, Authentication & RBAC
2. [MODULE-2-README.md](MODULE-2-README.md) — Sports Taxonomy & Match Data
3. [MODULE-3-README.md](MODULE-3-README.md) — Predictions & Odds Engine
4. [MODULE-4-README.md](MODULE-4-README.md) — Premium Subscriptions
5. [MODULE-5-README.md](MODULE-5-README.md) — Admin Panel
6. [MODULE-6-README.md](MODULE-6-README.md) — Blog / CMS
7. [MODULE-7-README.md](MODULE-7-README.md) — External Live API Integration
8. [MODULE-8-README.md](MODULE-8-README.md) — SEO, Performance & Production Hardening

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate

# create a MySQL database named `fieldforecasts` (or edit .env for sqlite/other),
# then:
php artisan migrate
php artisan storage:link
php artisan db:seed          # demo data across every module

php artisan serve
php artisan queue:work       # required for notifications, subscription
                              # settlement, and sports-data sync jobs
```

Set `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` in `.env` before seeding anywhere beyond local dev — otherwise `AdminUserSeeder` generates a random password and prints it once to the console.

For the scheduler (subscription expiry/reminders, sports-data sync — both safe no-ops until configured) add to cron:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## ⚠️ Before you rely on this in production

This entire project was built in a sandboxed environment with **no PHP binary and no Packagist/Composer registry access** — every file was hand-authored against Laravel 12 / PHP 8.4 conventions from training knowledge, and reviewed for internal consistency (route names, method signatures, brace balance, etc.), but **nothing has actually been executed**. Before treating this as production-ready:

- Run `composer install`, `php artisan migrate:fresh --seed`, and `php artisan test`
- Manually smoke-test: registration → login → admin panel access per role → subscribing → an Editor creating a prediction → an Admin settling it
- Review the Composer package integration points against whatever exact versions actually install (Breeze, Sanctum, Spatie Permission) — these are the parts most likely to have drifted
- Replace the seeded legal pages (Privacy Policy, Terms, Responsible Use) with real, reviewed legal text before any real launch
- Two bugs were found and fixed during a final consistency pass: `AdminDashboardController` was wired as an invokable route but only defined `index()`, and `tests/TestCase.php`/`phpunit.xml` didn't exist until Module 6. Both are fixed, but it's a reminder that a static read-through catches some things, not everything — testing is not optional here.

## Project structure

Standard Laravel 12 layout. Notable non-default pieces:
- `app/Services/` — business logic layer (one per domain: `PredictionService`, `SubscriptionService`, `MatchService`, etc.)
- `app/Repositories/` — used specifically where the data source will later be swapped for a live external API (`MatchRepositoryInterface`, `OddsRepositoryInterface`) — see Module 2/3 READMEs for why it's not used everywhere
- `app/Services/ExternalApi/` — the Module 7 provider abstraction (Fixture/Odds/Standings/LiveScore/Statistics APIs), with a Null-object fallback so the app works fully on manual data until a real vendor is contracted
- `app/Http/Controllers/Admin/` — the entire admin panel, separate from public controllers
- `routes/` — split by domain (`sports.php`, `predictions.php`, `subscriptions.php`, `admin.php`, `cms.php`, `seo.php`, `auth.php`) rather than one giant `web.php`
