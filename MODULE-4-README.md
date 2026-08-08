# Module 4 — Premium Subscriptions

Part of **Field Forecast**. Builds on Modules 1–3.

## What this module delivers

- **`subscription_plans`** — Weekly and Monthly only (no annual, per the brief), admin-adjustable `duration_days` decoupled from the billing-period label
- **`subscriptions`** — one row per subscription period (renewals insert a new row rather than mutating an old one, so "subscription history" is just a query, no separate history table needed), with `starts_at`/`ends_at`/`auto_renew`/`cancelled_at`/`renewal_reminder_sent_at`
- **`payments`** — billing history, gateway-agnostic (`gateway`/`gateway_reference` columns)
- **Payment gateway seam:** `PaymentGatewayInterface` + `ManualPaymentGateway` (instant-success stand-in), bound in `PaymentServiceProvider` — swap in a real Stripe/Paystack/M-Pesa driver later without touching `SubscriptionService` or any controller
- **`SubscriptionService`:** `subscribe()` (creates pending Payment → charges via gateway → activates on success, all in one transaction), `cancel()` (stops auto-renew, access continues until `ends_at` — doesn't yank access from someone who already paid), `expireDueSubscriptions()`, `sendRenewalReminders()`
- **Events/Listeners:** `SubscriptionActivated` → `ActivatePremiumAccess` (the only writer of `users.is_premium`/`premium_expires_at` on activation) and `SubscriptionExpired` → `RevokePremiumAccess` (only revokes if the expired subscription is actually the one backing current access — guards against a race if the user already renewed early)
- **Scheduled task:** `subscriptions:process` artisan command, registered daily at 01:00 in `routes/console.php` (`withoutOverlapping`, `onOneServer`) — expires lapsed subscriptions and sends renewal reminders (3-day window, sent once per subscription via `renewal_reminder_sent_at`)
- **Notification:** `SubscriptionRenewalReminder` (mail + database, queued)
- **Public/user UI:** plans page (`/subscriptions`), subscribe action, "My subscription" billing-history page with cancel-auto-renew action
- **Dashboard's** Subscription widget (reserved since Module 1) is now live
- **Fixed a gap from Module 1:** the base `App\Http\Controllers\Controller` class (with `AuthorizesRequests`/`ValidatesRequests`) was missing since Module 1 — every controller extends it, and any `$this->authorize()` call (Profile account deletion, this module's subscription cancel) would have fatally errored without it. Added now, retroactively fixing all three prior modules.

## Folder structure (this module's additions)

```
app/
  Models/ (SubscriptionPlan, Subscription, Payment)
  Services/
    SubscriptionService.php
    Payments/ (PaymentGatewayInterface, PaymentResult, ManualPaymentGateway)
  Events/ (SubscriptionActivated, SubscriptionExpired, SubscriptionCancelled)
  Listeners/ (ActivatePremiumAccess, RevokePremiumAccess)
  Notifications/SubscriptionRenewalReminder.php
  Policies/SubscriptionPolicy.php
  Providers/PaymentServiceProvider.php
  Console/Commands/ProcessSubscriptions.php
  Http/
    Controllers/SubscriptionController.php
    Requests/SubscribeRequest.php
    Controllers/Controller.php  (retroactive fix — see above)
database/
  migrations/ (subscription_plans, subscriptions, payments)
  factories/ (SubscriptionPlan)
  seeders/SubscriptionPlanSeeder.php
resources/views/subscriptions/ (plans, mine)
routes/subscriptions.php
tests/Feature/SubscriptionTest.php
```

## Setup additions

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\SubscriptionPlanSeeder
```

Register `App\Providers\PaymentServiceProvider` in `bootstrap/providers.php` alongside the others. Add a cron entry pointing at Laravel's scheduler so `subscriptions:process` actually runs daily:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Design notes

- **Why cancellation doesn't revoke access immediately:** the user already paid for the current period. `cancel()` only flips `auto_renew` off and stamps `cancelled_at`; the subscription stays `active` until `ends_at`, and the daily `expireDueSubscriptions()` sweep is what eventually revokes access and fires `SubscriptionExpired`. This is standard SaaS billing behavior and avoids a support-ticket-generating "I paid but got cut off early" complaint.
- **Why `auto_renew` exists but doesn't actually auto-charge:** there's no live gateway yet (`ManualPaymentGateway` can't re-charge a saved payment method — there isn't one). The column is there so the UI/data model doesn't need to change once real recurring billing is wired in; today, every subscription simply lapses at `ends_at` regardless of the flag.
- **Why a full `Subscription` row per period instead of one row that gets extended:** gives an accurate "Subscription history" for free (brief requirement) and makes the accuracy of `expireDueSubscriptions()` trivial — it only ever looks at rows whose specific `ends_at` has passed, never at "the" subscription's mutable state.
- **Race-guard on revocation:** `RevokePremiumAccess` checks that `premium_expires_at` still equals the expiring subscription's `ends_at` before turning off `is_premium` — if the user renewed/upgraded before the old one technically expired, revocation is skipped so their newer access isn't clobbered by a stale event.

## What's intentionally deferred

- Real payment gateway (Stripe/Paystack/M-Pesa) — swap `PaymentGatewayInterface`'s binding in `PaymentServiceProvider`; `SubscriptionService`, controllers, and views need no changes
- Admin panel screens for Manage Subscription Plans / Manage Payments — Module 5
- Revenue/subscription-growth analytics dashboards — Module 5 (Analytics section of the brief)
- Premium badge display next to usernames elsewhere in the UI (currently only shown on the dashboard) — trivial addition once Module 5's admin user list needs it too

## Next module

**Module 5 — Admin Panel**: Dashboard, Manage Users/Predictions/Sports/Countries/Leagues/Teams/Matches/Odds/Markets/Subscription Plans/Payments, Reports, Logs (surfacing `activity_logs`), Settings — wiring the CRUD screens on top of the services/policies/requests already built in Modules 1–4. Say "continue" when ready.
