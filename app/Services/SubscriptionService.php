<?php

namespace App\Services;

use App\Events\SubscriptionActivated;
use App\Events\SubscriptionCancelled;
use App\Events\SubscriptionExpired;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\SubscriptionRenewalReminder;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionService
{
    public function __construct(
        private readonly PaymentGatewayInterface $gateway,
        private readonly ActivityLogger $activityLogger,
    ) {
    }

    /**
     * Creates a pending Payment, charges it via the configured gateway,
     * and — only on success — activates the subscription. Wrapped in a
     * transaction so a failed charge never leaves an activated
     * subscription behind.
     */
    public function subscribe(User $user, SubscriptionPlan $plan): Subscription
    {
        return DB::transaction(function () use ($user, $plan) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => Subscription::STATUS_PENDING,
            ]);

            $payment = Payment::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount_cents' => $plan->price_cents,
                'currency' => $plan->currency,
                'status' => Payment::STATUS_PENDING,
                'gateway' => 'manual',
            ]);

            $result = $this->gateway->charge($payment);

            if (! $result->successful) {
                $payment->update(['status' => Payment::STATUS_FAILED]);
                $subscription->update(['status' => Subscription::STATUS_CANCELLED]);

                throw new RuntimeException($result->failureReason ?? 'Payment failed.');
            }

            $payment->update([
                'status' => Payment::STATUS_COMPLETED,
                'gateway_reference' => $result->reference,
                'paid_at' => now(),
            ]);

            $subscription->update([
                'status' => Subscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days),
            ]);

            event(new SubscriptionActivated($subscription));
            $this->activityLogger->log('subscription.activated', $user, $subscription, ['plan' => $plan->slug]);

            return $subscription->fresh();
        });
    }

    /**
     * Marks a subscription as not-renewing. Access is intentionally
     * NOT revoked immediately — the user keeps premium access through
     * `ends_at` (already paid for), and the daily expiry job revokes
     * it naturally when that date passes. See RevokePremiumAccess.
     */
    public function cancel(Subscription $subscription): Subscription
    {
        $subscription->update([
            'auto_renew' => false,
            'cancelled_at' => now(),
        ]);

        event(new SubscriptionCancelled($subscription));
        $this->activityLogger->log('subscription.cancelled', $subscription->user, $subscription);

        return $subscription;
    }

    /**
     * Called by the daily scheduled command. Expires any active
     * subscription whose access window has passed, regardless of
     * whether it was previously cancelled or still auto-renewing
     * (auto-renew has no gateway to actually charge yet — see
     * Module design notes — so every subscription lapses at period
     * end until real recurring billing is wired in).
     */
    public function expireDueSubscriptions(): int
    {
        $count = 0;

        Subscription::pastDue()->each(function (Subscription $subscription) use (&$count) {
            $subscription->update(['status' => Subscription::STATUS_EXPIRED]);
            event(new SubscriptionExpired($subscription));
            $count++;
        });

        return $count;
    }

    /** Sends a renewal reminder to subscriptions expiring within the given window, once per subscription (guarded by renewal_reminder_sent_at). */
    public function sendRenewalReminders(int $withinDays = 3): int
    {
        $count = 0;

        Subscription::with(['user', 'plan'])->expiringWithin($withinDays)->each(function (Subscription $subscription) use (&$count) {
            $subscription->user->notify(new SubscriptionRenewalReminder($subscription));
            $subscription->update(['renewal_reminder_sent_at' => now()]);
            $count++;
        });

        return $count;
    }
}
