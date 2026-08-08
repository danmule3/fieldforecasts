<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section_key' => 'hero',
                'type' => 'hero',
                'title' => 'Hero Section',
                'description' => 'Headline banner with call-to-action and sport quick links.',
                'content' => [
                    'headline' => "Smarter sports predictions,\nbacked by data.",
                    'subheadline' => 'Field Forecast publishes daily match predictions, odds information, and expert analysis across football, basketball, tennis, rugby, cricket and esports.',
                ],
            ],
            [
                'section_key' => 'stats',
                'type' => 'stats',
                'title' => 'Stats Bar',
                'description' => 'Quick trust-building numbers shown as a horizontal bar.',
                'content' => ['items' => []], // empty by default — populate via admin, bar hides itself until then
                'is_visible' => false,
            ],
            [
                'section_key' => 'features',
                'type' => 'features',
                'title' => 'Why Choose Field Forecast',
                'description' => 'Data-driven predictions, transparent accuracy tracking, and expert analysis in one place.',
                'content' => [
                    'items' => [
                        ['icon' => '📊', 'title' => 'Data-driven', 'text' => 'Every prediction backed by statistics, form, and head-to-head history.'],
                        ['icon' => '✅', 'title' => 'Transparent accuracy', 'text' => 'We publish our track record — nothing hidden.'],
                        ['icon' => '⚡', 'title' => 'Always current', 'text' => 'Live scores and odds, updated continuously.'],
                    ],
                ],
            ],
            [
                'section_key' => 'live_matches',
                'type' => 'live_matches',
                'title' => 'Live now',
            ],
            [
                'section_key' => 'todays_predictions',
                'type' => 'todays_predictions',
                'title' => "Today's predictions",
            ],
            [
                'section_key' => 'today_matches',
                'type' => 'today_matches',
                'title' => "Today's matches",
            ],
            [
                'section_key' => 'featured_matches',
                'type' => 'featured_matches',
                'title' => 'Featured',
            ],
            [
                'section_key' => 'upcoming_matches',
                'type' => 'upcoming_matches',
                'title' => 'Upcoming',
            ],
            [
                'section_key' => 'recent_winners',
                'type' => 'recent_winners',
                'title' => 'Recent winners',
            ],
            [
                'section_key' => 'about',
                'type' => 'content',
                'title' => 'About Field Forecast',
                'description' => 'Field Forecast is your trusted source for sports intelligence — predictions, statistics, and analysis, published transparently.',
                'is_visible' => false,
            ],
            [
                'section_key' => 'latest_articles',
                'type' => 'latest_articles',
                'title' => 'Latest articles',
            ],
            [
                'section_key' => 'testimonials',
                'type' => 'testimonials',
                'title' => 'What our users say',
            ],
            [
                'section_key' => 'newsletter',
                'type' => 'newsletter',
                'title' => 'Stay in the loop',
                'description' => "Get the week's top predictions and articles in your inbox.",
            ],
        ];

        foreach ($sections as $index => $section) {
            PageSection::updateOrCreate(
                ['page' => 'home', 'section_key' => $section['section_key']],
                [
                    'type' => $section['type'],
                    'title' => $section['title'] ?? null,
                    'description' => $section['description'] ?? null,
                    'content' => $section['content'] ?? null,
                    'is_visible' => $section['is_visible'] ?? true,
                    'display_order' => $index,
                ]
            );
        }
    }
}
