# Module 1 — Foundation, Authentication & RBAC

Part of **Field Forecast**, a Laravel 12 football & sports prediction platform (statistics, odds display, expert analysis — no betting).

## What this module delivers

- Laravel 12 project skeleton conventions (Blade + Alpine + Tailwind, no JS framework)
- `users` table + Spatie `laravel-permission` roles/permissions schema
- 6-tier role model: Guest (unauthenticated), Registered User, Premium User, Editor, Administrator, Super Administrator
- Full auth flow: register, login (rate-limited), logout, forgot/reset password, email verification, remember-me
- Profile management: update info + avatar, change password, soft-delete account (password-confirmed)
- Security-relevant activity log (`activity_logs` table + `ActivityLogger` service)
- `UserPolicy` + `AuthServiceProvider` gates (`access-admin-panel`, `view-premium-content`) — the premium gate is a stub other modules will extend when Subscriptions (Module 4) ships
- Feature tests for registration, login/rate-limiting, logout, and profile management

## Folder structure (this module's files only)

```
app/
  Http/
    Controllers/
      Auth/ (RegisteredUserController, AuthenticatedSessionController,
             PasswordResetLinkController, NewPasswordController, EmailVerificationController)
      ProfileController.php
      DashboardController.php
    Requests/
      Auth/ (RegisterRequest, LoginRequest, ChangePasswordRequest)
      UpdateProfileRequest.php
  Models/ (User.php, ActivityLog.php)
  Policies/ (UserPolicy.php)
  Providers/ (AuthServiceProvider.php)
  Services/ (AuthService.php, ActivityLogger.php)
database/
  migrations/ (users, cache/jobs, permission tables, activity_logs)
  seeders/ (RolesAndPermissionsSeeder, AdminUserSeeder, DatabaseSeeder)
  factories/ (UserFactory.php)
resources/views/
  layouts/ (app.blade.php, guest.blade.php)
  components/ (input, button, alert, navigation, footer)
  auth/ (register, login, forgot-password, reset-password, verify-email)
  profile/edit.blade.php
  dashboard.blade.php
  welcome.blade.php
routes/ (web.php, auth.php)
tests/Feature/ (Auth/RegistrationTest, Auth/AuthenticationTest, ProfileTest)
config/permission.php
```

## Setup (in a real Laravel 12 environment)

```bash
composer require spatie/laravel-permission laravel/sanctum laravel/breeze --dev
php artisan migrate
php artisan db:seed
```

Register `App\Providers\AuthServiceProvider` in `bootstrap/providers.php` and add the `Spatie\Permission\Traits\HasRoles` middleware aliases (`role`, `permission`, `role_or_permission`) in `bootstrap/app.php`'s `withMiddleware()`.

Set `SEED_ADMIN_EMAIL` and `SEED_ADMIN_PASSWORD` in `.env` before seeding in any non-local environment — `AdminUserSeeder` generates and prints a random password if these are absent, but that password only appears in console output, never persisted anywhere else.

## Security notes

- Login is throttled both by `LoginRequest::ensureIsNotRateLimited()` (5/email+IP) and a route-level `throttle:5,1` — belt-and-suspenders against brute force.
- Password reset always returns a generic success message regardless of whether the email exists, to prevent user enumeration.
- `Gate::before` grants Super Administrator unconditional access; Administrator cannot promote itself/others to Super Administrator (see `UserPolicy::changeRole`) — a specific privilege-escalation guard.
- Avatar uploads are stored under a per-user folder with a server-generated filename (Laravel's default `store()` behavior) — never a client-supplied filename, preventing path traversal / overwrite attacks.
- All account-changing actions (password change, email change, account deletion) write to `activity_logs`.

## What's intentionally deferred to later modules

- Homepage sections (Hero, Today's Predictions, Live Matches, etc.) — Module 2/3
- Sports/Countries/Leagues/Teams/Matches schema — Module 2
- Predictions, Odds, Prediction Results — Module 3
- Subscriptions, billing, premium access sync — Module 4 (the `is_premium`/`premium_expires_at` fast-path columns and `view-premium-content` gate are already in place for it to hook into)
- Admin panel CRUD screens — Module 5
- Blog/CMS — Module 6
- External API service layer (fixtures/odds/scores providers) — Module 7
- SEO metadata system, sitemap, schema.org — Module 8

## Next module

**Module 2 — Sports Taxonomy & Match Data**: migrations/models/services for Sports, Countries, Leagues, Teams, Matches, plus the homepage's live/upcoming match sections. Say "continue" or "build module 2" when ready.
