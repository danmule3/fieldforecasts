<?php

namespace App\Policies;

use App\Models\Prediction;
use App\Models\User;

class PredictionPolicy
{
    /** Free predictions are publicly visible; premium ones require the view-premium-content gate (checked separately in the controller/view). */
    public function view(?User $user, Prediction $prediction): bool
    {
        if (! $prediction->is_premium) {
            return true;
        }

        return $user !== null && ($user->hasActivePremiumAccess() || $user->isStaff());
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_EDITOR, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    public function update(User $user, Prediction $prediction): bool
    {
        return $user->hasAnyRole([User::ROLE_EDITOR, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    /** Settling an outcome is restricted to Admin+ — Editors can draft/edit but not finalize results (separation of duties). */
    public function settle(User $user, Prediction $prediction): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    public function delete(User $user, Prediction $prediction): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }
}
