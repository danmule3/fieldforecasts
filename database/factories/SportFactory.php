<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SportFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Football', 'Basketball', 'Tennis', 'Rugby', 'Cricket', 'Esports']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }
}
