<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class StandingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'league_id' => League::factory(),
            'team_id' => Team::factory(),
            'position' => fake()->numberBetween(1, 20),
            'played' => 10,
            'won' => fake()->numberBetween(0, 10),
            'drawn' => 2,
            'lost' => 2,
            'goals_for' => fake()->numberBetween(5, 25),
            'goals_against' => fake()->numberBetween(5, 25),
            'points' => fake()->numberBetween(0, 30),
        ];
    }
}
