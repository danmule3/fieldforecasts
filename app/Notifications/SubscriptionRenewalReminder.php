<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewalReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Subscription $subscription)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Field Forecast Premium access is ending soon')
            ->greeting("Hi {$notifiable->name},")
            ->line("Your {$this->subscription->plan->name} subscription ends on {$this->subscription->ends_at->format('d M Y')}.")
            ->action('Renew now', route('subscriptions.index'))
            ->line('Renew to keep uninterrupted access to premium predictions.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'ends_at' => $this->subscription->ends_at->toIso8601String(),
        ];
    }
}
