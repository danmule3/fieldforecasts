<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city() . ' ' . fake()->randomElement(['FC', 'United', 'City', 'Athletic', 'Rovers']);

        return [
            'sport_id' => Sport::factory(),
            'country_id' => Country::factory(),
            'name' => $name,
            'short_name' => Str::upper(Str::substr(preg_replace('/\s+/', '', $name), 0, 3)),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 99999),
        ];
    }
}
