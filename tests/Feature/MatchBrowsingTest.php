<?php

namespace Tests\Feature;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchBrowsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_with_no_matches(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_homepage_shows_live_and_upcoming_matches(): void
    {
        $league = League::factory()->create();

        $live = GameMatch::factory()->live()->create(['league_id' => $league->id, 'sport_id' => $league->sport_id]);
        $upcoming = GameMatch::factory()->create(['league_id' => $league->id, 'sport_id' => $league->sport_id]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($live->homeTeam->name);
        $response->assertSee($upcoming->homeTeam->name);
    }

    public function test_match_detail_page_renders(): void
    {
        $match = GameMatch::factory()->create();

        $this->get(route('matches.show', $match))
            ->assertOk()
            ->assertSee($match->homeTeam->name)
            ->assertSee($match->awayTeam->name);
    }

    public function test_matches_can_be_filtered_by_sport(): void
    {
        $football = Sport::factory()->create(['slug' => 'football']);
        $basketball = Sport::factory()->create(['slug' => 'basketball']);

        $footballLeague = League::factory()->create(['sport_id' => $football->id]);
        $basketballLeague = League::factory()->create(['sport_id' => $basketball->id]);

        $footballMatch = GameMatch::factory()->create(['sport_id' => $football->id, 'league_id' => $footballLeague->id]);
        $basketballMatch = GameMatch::factory()->create(['sport_id' => $basketball->id, 'league_id' => $basketballLeague->id]);

        $response = $this->get('/matches?sport=football');

        $response->assertOk();
        $response->assertSee($footballMatch->homeTeam->name);
        $response->assertDontSee($basketballMatch->homeTeam->name);
    }

    public function test_league_page_lists_its_matches(): void
    {
        $league = League::factory()->create();
        $match = GameMatch::factory()->create(['league_id' => $league->id, 'sport_id' => $league->sport_id]);

        $this->get(route('leagues.show', $league))
            ->assertOk()
            ->assertSee($match->homeTeam->name);
    }

    public function test_authenticated_user_can_follow_a_team(): void
    {
        $user = \App\Models\User::factory()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user)->post(route('teams.follow', $team));

        $response->assertRedirect();
        $this->assertTrue($user->favouriteTeams()->where('team_id', $team->id)->exists());
    }

    public function test_guest_cannot_follow_a_team(): void
    {
        $team = Team::factory()->create();

        $this->post(route('teams.follow', $team))->assertRedirect('/login');
    }
}
