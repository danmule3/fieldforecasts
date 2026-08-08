<?php

namespace App\Listeners;

use App\Events\PredictionSettled;

/**
 * Keeps `predictions.status` (fast-path, used by "Recent Winners" and
 * accuracy queries) in sync with the append-only prediction_results
 * audit log. This is the ONLY place that writes predictions.status —
 * see the migration comment on that column.
 */
class SyncPredictionFastPathStatus
{
    public function handle(PredictionSettled $event): void
    {
        $event->prediction->update(['status' => $event->result->outcome]);
    }
}
