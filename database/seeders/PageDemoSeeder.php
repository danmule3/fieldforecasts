<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageDemoSeeder extends Seeder
{
    /**
     * Seeds the legal/compliance pages a sports-prediction platform
     * needs from day one — placeholder copy, not legal advice. Replace
     * with actual reviewed policy text before going live.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Field Forecast',
                'slug' => 'about',
                'body' => "Field Forecast is a sports statistics and prediction platform. We publish match predictions, odds information, analysis, and statistics across football, basketball, tennis, rugby, cricket, and esports.\n\nField Forecast does not accept wagers, place bets, or facilitate gambling of any kind. All content is provided for informational purposes only.",
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'body' => "This placeholder privacy policy should be replaced with reviewed legal text describing what data Field Forecast collects (account details, usage data), how it's used, and users' rights before this platform goes live.",
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'body' => "This placeholder terms of service should be replaced with reviewed legal text covering acceptable use, subscription terms, and liability limitations before this platform goes live.",
            ],
            [
                'title' => 'Responsible Use',
                'slug' => 'responsible-use',
                'body' => "Field Forecast predictions and odds information are provided for entertainment and informational purposes only. Field Forecast does not accept wagers. If you choose to bet with a licensed operator elsewhere, please do so responsibly and within your means.",
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], [...$page, 'is_active' => true]);
        }
    }
}
