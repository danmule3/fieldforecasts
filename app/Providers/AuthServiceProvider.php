<?php

namespace App\Providers;

use App\Models\Prediction;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\PredictionPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Prediction::class => PredictionPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
    ];

    public function boot(): void
    {
        // Super Administrator bypasses all ability checks.
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole(User::ROLE_SUPER_ADMIN) ?: null;
        });

        Gate::define('access-admin-panel', function (User $user) {
            return $user->isStaff();
        });

        // Editors manage taxonomy/content (sports, leagues, teams, matches,
        // predictions, odds); Administrator+ additionally manage users,
        // roles, subscriptions, payments, and settings via explicit
        // permission checks / role checks in those controllers, not this gate.
        Gate::define('manage-content', function (User $user) {
            return $user->hasAnyRole([User::ROLE_EDITOR, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
        });

        Gate::define('manage-system', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
        });

        // Used to gate premium prediction content in views/controllers
        // ahead of the full Subscriptions module (Module 4).
        Gate::define('view-premium-content', function (User $user) {
            return $user->hasActivePremiumAccess() || $user->isStaff();
        });
    }
}
