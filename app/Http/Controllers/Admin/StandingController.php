<?php

namespace App\Http\Controllers\Admin;

use App\Models\League;
use App\Models\Standing;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manual standings entry — same rationale as Module 3's odds admin
 * screen: an Editor needs a fallback path before/without the
 * Standings API integration, keyed by league since a standings row is
 * meaningless without one.
 */
class StandingController extends AdminController
{
    public function index(League $league): View
    {
        return view('admin.standings.index', [
            'league' => $league,
            'standings' => $league->standings()->with('team')->get(),
            'teams' => Team::where('sport_id', $league->sport_id)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, League $league): RedirectResponse
    {
        $data = $request->validate([
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'position' => ['required', 'integer', 'min:0'],
            'played' => ['required', 'integer', 'min:0'],
            'won' => ['required', 'integer', 'min:0'],
            'drawn' => ['required', 'integer', 'min:0'],
            'lost' => ['required', 'integer', 'min:0'],
            'goals_for' => ['required', 'integer'],
            'goals_against' => ['required', 'integer'],
            'points' => ['required', 'integer'],
        ]);

        Standing::updateOrCreate(
            ['league_id' => $league->id, 'team_id' => $data['team_id'], 'season' => $league->season],
            $data
        );

        return redirect()->route('admin.standings.index', $league)->with('status', 'Standing saved.');
    }

    public function destroy(League $league, Standing $standing): RedirectResponse
    {
        $standing->delete();

        return redirect()->route('admin.standings.index', $league)->with('status', 'Standing removed.');
    }
}
