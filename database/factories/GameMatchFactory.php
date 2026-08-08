<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameMatch>
 */
class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        // Note: when seeding real data, always pass an explicit
        // sport_id matching the league's sport_id — the factory
        // defaults are independent for isolated unit-test convenience
        // only, not guaranteed consistent with each other.
        return [
            'sport_id' => \App\Models\Sport::factory(),
            'league_id' => League::factory(),
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'kickoff_at' => fake()->dateTimeBetween('now', '+2 weeks'),
            'status' => GameMatch::STATUS_SCHEDULED,
        ];
    }

    public function live(): static
    {
        return $this->state(fn () => [
            'status' => GameMatch::STATUS_LIVE,
            'kickoff_at' => now()->subMinutes(fake()->numberBetween(1, 80)),
            'home_score' => fake()->numberBetween(0, 4),
            'away_score' => fake()->numberBetween(0, 4),
            'minute' => fake()->numberBetween(1, 90),
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn () => [
            'status' => GameMatch::STATUS_FINISHED,
            'kickoff_at' => fake()->dateTimeBetween('-2 weeks', 'now'),
            'home_score' => fake()->numberBetween(0, 4),
            'away_score' => fake()->numberBetween(0, 4),
        ]);
    }

    public function today(): static
    {
        return $this->state(fn () => [
            'kickoff_at' => now()->setTime(fake()->numberBetween(12, 22), fake()->randomElement([0, 15, 30, 45])),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
