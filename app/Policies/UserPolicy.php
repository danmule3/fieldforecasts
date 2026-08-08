<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->can('users.view');
    }

    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->can('users.update');
    }

    /**
     * Only Super Administrator may change roles — Administrator can
     * manage users but must not be able to promote itself or others
     * to Super Administrator (privilege escalation guard).
     */
    public function changeRole(User $user, User $target): bool
    {
        return $user->hasRole(User::ROLE_SUPER_ADMIN)
            && $user->id !== $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        return $user->id !== $target->id
            && $user->hasRole(User::ROLE_SUPER_ADMIN);
    }

    public function deleteOwnAccount(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }
}
