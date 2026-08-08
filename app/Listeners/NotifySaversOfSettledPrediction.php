<?php

namespace App\Listeners;

use App\Events\PredictionSettled;
use App\Notifications\PredictionSettledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Queued (not synchronous) — settlement can affect large numbers of
 * users who saved a popular prediction, so notifying them must not
 * block the request that triggered settlement (an Editor/Admin action).
 */
class NotifySaversOfSettledPrediction implements ShouldQueue
{
    public function handle(PredictionSettled $event): void
    {
        $event->prediction->savedBy()
            ->chunk(100, function ($users) use ($event) {
                foreach ($users as $user) {
                    $user->notify(new PredictionSettledNotification($event->prediction, $event->result));
                }
            });
    }
}
