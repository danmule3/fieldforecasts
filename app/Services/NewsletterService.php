<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(string $email): NewsletterSubscriber
    {
        return NewsletterSubscriber::updateOrCreate(
            ['email' => $email],
            [
                'token' => Str::random(40),
                'subscribed_at' => now(),
                'unsubscribed_at' => null, // re-subscribing clears a prior unsubscribe
            ]
        );
    }

    public function unsubscribe(string $token): bool
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (! $subscriber) {
            return false;
        }

        $subscriber->update(['unsubscribed_at' => now()]);

        return true;
    }
}
