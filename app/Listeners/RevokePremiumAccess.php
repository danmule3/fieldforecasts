<?php

namespace App\Listeners;

use App\Events\SubscriptionExpired;

class RevokePremiumAccess
{
    public function handle(SubscriptionExpired $event): void
    {
        $user = $event->subscription->user;

        // Only revoke if this expired subscription was the one backing
        // their current access — guards against a race where a newer
        // subscription already replaced it (e.g. they upgraded early).
        if ($user->premium_expires_at?->equalTo($event->subscription->ends_at)) {
            $user->forceFill(['is_premium' => false])->save();
        }
    }
}
