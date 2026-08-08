<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function show(Team $team): View
    {
        $team->load(['sport', 'country']);

        $upcoming = GameMatch::query()
            ->with(['league', 'homeTeam', 'awayTeam'])
            ->where(fn ($q) => $q->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
            ->upcoming()
            ->orderBy('kickoff_at')
            ->limit(10)
            ->get();

        return view('teams.show', [
            'team' => $team,
            'upcoming' => $upcoming,
            'isFollowing' => auth()->check() ? $team->followers()->where('user_id', auth()->id())->exists() : false,
        ]);
    }

    /**
     * Toggle the authenticated user's favourite-team status. Powers the
     * "Favourite Teams" dashboard widget reserved in Module 1.
     */
    public function toggleFollow(Request $request, Team $team): RedirectResponse
    {
        $user = $request->user();

        if ($user->favouriteTeams()->where('team_id', $team->id)->exists()) {
            $user->favouriteTeams()->detach($team->id);
        } else {
            $user->favouriteTeams()->attach($team->id);
        }

        return back();
    }
}
