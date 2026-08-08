<?php

namespace App\Notifications;

use App\Models\Prediction;
use App\Models\PredictionResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PredictionSettledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Prediction $prediction,
        public readonly PredictionResult $result,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $match = $this->prediction->match;
        $outcome = ucfirst($this->result->outcome);

        return (new MailMessage)
            ->subject("Prediction settled: {$outcome}")
            ->greeting("Your saved prediction has been settled: {$outcome}")
            ->line("{$match->homeTeam->name} vs {$match->awayTeam->name} — pick: {$this->prediction->pick}")
            ->action('View prediction', route('predictions.show', $this->prediction))
            ->line('Thanks for using Field Forecast.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'prediction_id' => $this->prediction->id,
            'match_id' => $this->prediction->match_id,
            'outcome' => $this->result->outcome,
        ];
    }
}
