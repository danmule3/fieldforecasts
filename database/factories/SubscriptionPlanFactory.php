<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name' => 'Weekly Premium',
            'slug' => 'weekly-premium-' . fake()->unique()->numberBetween(1, 99999),
            'billing_period' => SubscriptionPlan::PERIOD_WEEKLY,
            'duration_days' => 7,
            'price_cents' => 499,
            'currency' => 'USD',
            'features' => ['Full analysis on every prediction', 'Priority support'],
            'is_active' => true,
        ];
    }

    public function monthly(): static
    {
        return $this->state(fn () => [
            'name' => 'Monthly Premium',
            'billing_period' => SubscriptionPlan::PERIOD_MONTHLY,
            'duration_days' => 30,
            'price_cents' => 1499,
        ]);
    }
}
