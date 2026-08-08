<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['category' => 'General', 'question' => 'Is Field Forecast a betting site?', 'answer' => 'No. Field Forecast publishes predictions, statistics, and odds information for informational purposes only. We never accept wagers or facilitate betting.'],
            ['category' => 'General', 'question' => 'How accurate are your predictions?', 'answer' => 'Our overall accuracy is published live on the Predictions page and homepage, calculated from every settled prediction.'],
            ['category' => 'Subscriptions', 'question' => 'What does Premium include?', 'answer' => 'Premium unlocks full analysis, reasoning, recent form, and head-to-head statistics on every prediction.'],
            ['category' => 'Subscriptions', 'question' => 'Can I cancel anytime?', 'answer' => 'Yes — cancelling stops auto-renewal, and you keep access until the end of your current billing period.'],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                [...$faq, 'display_order' => $index, 'is_active' => true]
            );
        }
    }
}
