<?php

namespace Tests\Feature;

use App\Jobs\SyncLeagueFixturesJob;
use App\Models\ApiKey;
use App\Models\GameMatch;
use App\Models\League;
use App\Models\Standing;
use App\Models\Team;
use App\Models\User;
use App\Services\ExternalApi\Contracts\FixtureProviderInterface;
use App\Services\ExternalApi\NullSportsDataProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_null_provider_is_bound_when_no_active_api_key_exists(): void
    {
        $provider = app(FixtureProviderInterface::class);

        $this->assertInstanceOf(NullSportsDataProvider::class, $provider);
    }

    public function test_real_provider_is_bound_once_an_active_api_key_exists(): void
    {
        ApiKey::create([
            'provider' => 'sportsdata',
            'label' => 'Test key',
            'key_value' => 'secret-value',
            'is_active' => true,
        ]);

        $provider = app(FixtureProviderInterface::class);

        $this->assertInstanceOf(\App\Services\ExternalApi\SportsDataApiProvider::class, $provider);
    }

    public function test_api_key_value_is_encrypted_at_rest(): void
    {
        $apiKey = ApiKey::create([
            'provider' => 'sportsdata',
            'label' => 'Test key',
            'key_value' => 'super-secret-value',
            'is_active' => true,
        ]);

        $raw = \DB::table('api_keys')->where('id', $apiKey->id)->value('key_value');

        $this->assertNotSame('super-secret-value', $raw);
        $this->assertSame('super-secret-value', $apiKey->fresh()->key_value);
    }

    public function test_masked_key_only_reveals_last_four_characters(): void
    {
        $apiKey = ApiKey::create([
            'provider' => 'sportsdata',
            'label' => 'Test key',
            'key_value' => 'abcd1234wxyz',
            'is_active' => true,
        ]);

        $this->assertStringEndsWith('wxyz', $apiKey->masked());
        $this->assertStringNotContainsString('abcd1234', $apiKey->masked());
    }

    public function test_fixture_sync_job_upserts_matches_idempotently(): void
    {
        ApiKey::create(['provider' => 'sportsdata', 'label' => 'k', 'key_value' => 'v', 'is_active' => true]);

        $league = League::factory()->create(['external_ref' => 'lg-1']);

        Http::fake([
            '*' => Http::response([
                'fixtures' => [
                    [
                        'id' => 'fx-1',
                        'home_team' => ['id' => 'ht-1', 'name' => 'Home FC'],
                        'away_team' => ['id' => 'at-1', 'name' => 'Away FC'],
                        'kickoff_at' => now()->addDay()->toIso8601String(),
                        'venue' => 'Test Stadium',
                    ],
                ],
            ], 200),
        ]);

        SyncLeagueFixturesJob::dispatchSync($league);
        $this->assertDatabaseCount('matches', 1);

        // Running again must not create a duplicate — same external_ref.
        SyncLeagueFixturesJob::dispatchSync($league);
        $this->assertDatabaseCount('matches', 1);

        $this->assertDatabaseHas('teams', ['name' => 'Home FC', 'external_ref' => 'ht-1']);
    }

    public function test_sync_job_is_a_noop_without_an_api_key(): void
    {
        $league = League::factory()->create(['external_ref' => 'lg-1']);

        Http::fake(); // would fail the test if any request were attempted

        SyncLeagueFixturesJob::dispatchSync($league);

        Http::assertNothingSent();
        $this->assertDatabaseCount('matches', 0);
    }

    public function test_league_standings_page_shows_seeded_standings(): void
    {
        $league = League::factory()->create();
        $team = Team::factory()->create(['sport_id' => $league->sport_id]);
        Standing::factory()->create(['league_id' => $league->id, 'team_id' => $team->id, 'position' => 1]);

        $this->get(route('leagues.show', $league))
            ->assertOk()
            ->assertSee($team->name);
    }

    public function test_only_administrator_plus_can_manage_api_keys(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);

        $this->actingAs($editor)->get(route('admin.api-keys.index'))->assertForbidden();

        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);

        $this->actingAs($admin)->get(route('admin.api-keys.index'))->assertOk();
    }
}
