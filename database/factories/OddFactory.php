<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

class OddFactory extends Factory
{
    public function definition(): array
    {
        return [
            'match_id' => GameMatch::factory(),
            'market_id' => Market::factory(),
            'selection' => fake()->randomElement(['Home', 'Draw', 'Away']),
            'price' => fake()->randomFloat(2, 1.2, 5.5),
            'provider' => 'manual',
        ];
    }
}
