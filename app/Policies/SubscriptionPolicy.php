<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id || $user->can('users.view');
    }

    public function cancel(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id && $subscription->isActive();
    }
}
