<?php

namespace Tests\Feature;

use App\Models\GameMatch;
use App\Models\Market;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_prediction_is_visible_to_guests(): void
    {
        $prediction = Prediction::factory()->create(['is_premium' => false, 'published_at' => now()]);

        $this->get(route('predictions.show', $prediction))
            ->assertOk()
            ->assertSee($prediction->analysis);
    }

    public function test_premium_prediction_is_blurred_for_guests(): void
    {
        $prediction = Prediction::factory()->premium()->create(['published_at' => now()]);

        $response = $this->get(route('predictions.show', $prediction));

        $response->assertOk();
        $response->assertDontSee($prediction->analysis);
        $response->assertSee('Premium prediction');
    }

    public function test_premium_prediction_is_visible_to_premium_users(): void
    {
        $prediction = Prediction::factory()->premium()->create(['published_at' => now()]);
        $user = User::factory()->premium()->create();

        $response = $this->actingAs($user)->get(route('predictions.show', $prediction));

        $response->assertOk();
        $response->assertSee($prediction->analysis);
    }

    public function test_premium_prediction_hidden_from_expired_premium_user(): void
    {
        $prediction = Prediction::factory()->premium()->create(['published_at' => now()]);
        $user = User::factory()->create(['is_premium' => true, 'premium_expires_at' => now()->subDay()]);

        $response = $this->actingAs($user)->get(route('predictions.show', $prediction));

        $response->assertDontSee($prediction->analysis);
    }

    public function test_authenticated_user_can_save_and_unsave_a_prediction(): void
    {
        $user = User::factory()->create();
        $prediction = Prediction::factory()->create(['published_at' => now()]);

        $this->actingAs($user)->post(route('predictions.save', $prediction));
        $this->assertTrue($user->savedPredictions()->where('prediction_id', $prediction->id)->exists());

        $this->actingAs($user)->post(route('predictions.save', $prediction));
        $this->assertFalse($user->savedPredictions()->where('prediction_id', $prediction->id)->exists());
    }

    public function test_settling_a_prediction_syncs_status_and_creates_audit_row(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);

        $prediction = Prediction::factory()->create(['status' => Prediction::STATUS_PENDING]);

        app(\App\Services\PredictionService::class)->settle($prediction, Prediction::STATUS_WON, $admin, 'Final score confirmed.');

        $this->assertSame(Prediction::STATUS_WON, $prediction->fresh()->status);
        $this->assertDatabaseHas('prediction_results', [
            'prediction_id' => $prediction->id,
            'outcome' => 'won',
            'settled_by' => $admin->id,
        ]);
    }

    public function test_editor_cannot_settle_a_prediction(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);

        $prediction = Prediction::factory()->create();

        $this->assertFalse($editor->can('settle', $prediction));
    }

    public function test_accuracy_percentage_reflects_settled_predictions(): void
    {
        Prediction::factory()->count(3)->won()->create();
        Prediction::factory()->count(1)->lost()->create();
        Prediction::factory()->create(['status' => Prediction::STATUS_PENDING]); // excluded

        $accuracy = app(\App\Services\PredictionService::class)->accuracyPercentage();

        $this->assertSame(75.0, $accuracy);
    }
}
