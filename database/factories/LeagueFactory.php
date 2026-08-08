<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeagueFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true) . ' League';

        return [
            'sport_id' => Sport::factory(),
            'country_id' => Country::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 99999),
            'season' => '2026/2027',
            'is_active' => true,
        ];
    }
}
