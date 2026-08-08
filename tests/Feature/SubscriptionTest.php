<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_plans_page_renders(): void
    {
        SubscriptionPlan::factory()->create();

        $this->get('/subscriptions')->assertOk();
    }

    public function test_subscribing_activates_premium_access(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create(['duration_days' => 7]);

        $this->actingAs($user)->post(route('subscriptions.store'), ['plan' => $plan->slug])
            ->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertTrue($user->hasActivePremiumAccess());
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'status' => Subscription::STATUS_ACTIVE,
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'status' => 'completed',
        ]);
    }

    public function test_guest_cannot_subscribe(): void
    {
        $plan = SubscriptionPlan::factory()->create();

        $this->post(route('subscriptions.store'), ['plan' => $plan->slug])
            ->assertRedirect('/login');
    }

    public function test_user_can_cancel_own_subscription_but_keeps_access_until_end_date(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $subscription = app(SubscriptionService::class)->subscribe($user, $plan);

        $this->actingAs($user)->post(route('subscriptions.cancel', $subscription))
            ->assertRedirect();

        $subscription->refresh();
        $this->assertNotNull($subscription->cancelled_at);
        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertTrue($user->fresh()->hasActivePremiumAccess());
    }

    public function test_user_cannot_cancel_someone_elses_subscription(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $subscription = app(SubscriptionService::class)->subscribe($owner, $plan);

        $this->actingAs($intruder)->post(route('subscriptions.cancel', $subscription))
            ->assertForbidden();
    }

    public function test_expiring_subscriptions_revoke_premium_access(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $subscription = app(SubscriptionService::class)->subscribe($user, $plan);
        $subscription->update(['ends_at' => now()->subDay()]);

        $expiredCount = app(SubscriptionService::class)->expireDueSubscriptions();

        $this->assertSame(1, $expiredCount);
        $this->assertFalse($user->fresh()->hasActivePremiumAccess());
        $this->assertSame(Subscription::STATUS_EXPIRED, $subscription->fresh()->status);
    }

    public function test_renewal_reminders_are_sent_once_per_subscription(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();
        $subscription = app(SubscriptionService::class)->subscribe($user, $plan);
        $subscription->update(['ends_at' => now()->addDays(2)]);

        $service = app(SubscriptionService::class);
        $first = $service->sendRenewalReminders(3);
        $second = $service->sendRenewalReminders(3); // should be a no-op the second time

        $this->assertSame(1, $first);
        $this->assertSame(0, $second);

        \Illuminate\Support\Facades\Notification::assertSentTo($user, \App\Notifications\SubscriptionRenewalReminder::class);
    }
}
