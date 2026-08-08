<?php

namespace App\Providers;

use App\Events\PredictionSettled;
use App\Events\SubscriptionActivated;
use App\Events\SubscriptionExpired;
use App\Listeners\ActivatePremiumAccess;
use App\Listeners\NotifySaversOfSettledPrediction;
use App\Listeners\RevokePremiumAccess;
use App\Listeners\SyncPredictionFastPathStatus;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PredictionSettled::class => [
            // Order matters: sync the fast-path status first so the
            // notification (and anything it renders) sees the final state.
            SyncPredictionFastPathStatus::class,
            NotifySaversOfSettledPrediction::class,
        ],
        SubscriptionActivated::class => [
            ActivatePremiumAccess::class,
        ],
        SubscriptionExpired::class => [
            RevokePremiumAccess::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
