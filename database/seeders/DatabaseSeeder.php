<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            SportSeeder::class,
            CountrySeeder::class,
            SportsDemoDataSeeder::class,
            MarketSeeder::class,
            PredictionsDemoSeeder::class,
            SubscriptionPlanSeeder::class,
            SettingsSeeder::class,
            CategoryTagSeeder::class,
            ArticleDemoSeeder::class,
            FaqDemoSeeder::class,
            TestimonialDemoSeeder::class,
            PageDemoSeeder::class,
            MenuDemoSeeder::class,
            PageSectionSeeder::class,
        ]);
    }
}
