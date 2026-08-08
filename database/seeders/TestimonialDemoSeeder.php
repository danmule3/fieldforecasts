<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialDemoSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'James O.', 'role' => 'Premium subscriber', 'quote' => 'The analysis depth is what sold me — not just a pick, but the reasoning behind it.'],
            ['name' => 'Amara K.', 'role' => 'Free member', 'quote' => 'Clean, fast, and the accuracy tracking makes it easy to trust the platform.'],
            ['name' => 'Daniel R.', 'role' => 'Premium subscriber', 'quote' => 'Been using it for two months — the head-to-head stats are a nice touch.'],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::firstOrCreate(
                ['name' => $testimonial['name']],
                [...$testimonial, 'display_order' => $index, 'is_active' => true]
            );
        }
    }
}
