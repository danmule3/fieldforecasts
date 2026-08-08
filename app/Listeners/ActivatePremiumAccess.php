<?php

namespace App\Listeners;

use App\Events\SubscriptionActivated;

/**
 * The ONLY writer of users.is_premium / premium_expires_at on
 * activation — see User migration comment (Module 1) about these
 * being a denormalized fast path whose source of truth is the
 * subscriptions table.
 */
class ActivatePremiumAccess
{
    public function handle(SubscriptionActivated $event): void
    {
        $event->subscription->user->forceFill([
            'is_premium' => true,
            'premium_expires_at' => $event->subscription->ends_at,
        ])->save();
    }
}
