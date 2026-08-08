<?php

namespace App\Events;

use App\Models\Prediction;
use App\Models\PredictionResult;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PredictionSettled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Prediction $prediction,
        public readonly PredictionResult $result,
    ) {
    }
}
