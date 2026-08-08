<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Market;
use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prediction>
 */
class PredictionFactory extends Factory
{
    protected $model = Prediction::class;

    public function definition(): array
    {
        return [
            'match_id' => GameMatch::factory(),
            'market_id' => Market::factory(),
            'pick' => fake()->randomElement(['Home', 'Draw', 'Away', 'Over 2.5', 'BTTS Yes']),
            'confidence' => fake()->numberBetween(50, 95),
            'analysis' => fake()->paragraph(4),
            'reasoning' => fake()->paragraph(2),
            'is_premium' => fake()->boolean(30),
            'status' => Prediction::STATUS_PENDING,
            'published_at' => now(),
        ];
    }

    public function premium(): static
    {
        return $this->state(fn () => ['is_premium' => true]);
    }

    public function won(): static
    {
        return $this->state(fn () => ['status' => Prediction::STATUS_WON]);
    }

    public function lost(): static
    {
        return $this->state(fn () => ['status' => Prediction::STATUS_LOST]);
    }
}
