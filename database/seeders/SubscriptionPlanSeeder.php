<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Prices are illustrative USD placeholders — adjust currency/amount
     * per market before going live (config is per-plan, not hardcoded).
     */
    public function run(): void
    {
        SubscriptionPlan::firstOrCreate(
            ['slug' => 'weekly-premium'],
            [
                'name' => 'Weekly Premium',
                'billing_period' => SubscriptionPlan::PERIOD_WEEKLY,
                'duration_days' => 7,
                'price_cents' => 499,
                'currency' => 'USD',
                'features' => [
                    'Full analysis, reasoning & statistics on every prediction',
                    'Access to all premium picks for 7 days',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::firstOrCreate(
            ['slug' => 'monthly-premium'],
            [
                'name' => 'Monthly Premium',
                'billing_period' => SubscriptionPlan::PERIOD_MONTHLY,
                'duration_days' => 30,
                'price_cents' => 1499,
                'currency' => 'USD',
                'features' => [
                    'Full analysis, reasoning & statistics on every prediction',
                    'Access to all premium picks for 30 days',
                    'Best value per week',
                ],
                'is_active' => true,
            ]
        );
    }
}
