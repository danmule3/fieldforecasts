<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MarketFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['1X2', 'Both Teams to Score', 'Over 2.5 Goals', 'Draw No Bet']);

        return [
            'key' => \Illuminate\Support\Str::slug($name, '_'),
            'name' => $name,
            'is_active' => true,
        ];
    }
}
