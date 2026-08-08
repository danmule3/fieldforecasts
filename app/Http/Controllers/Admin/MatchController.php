<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends AdminController
{
    public function index(Request $request): View
    {
        $matches = GameMatch::with(['sport', 'league', 'homeTeam', 'awayTeam'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('kickoff_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.matches.index', ['matches' => $matches]);
    }

    public function create(): View
    {
        return view('admin.matches.form', $this->formData(new GameMatch()));
    }

    public function store(Request $request): RedirectResponse
    {
        GameMatch::create($this->validated($request));

        return redirect()->route('admin.matches.index')->with('status', 'Match created.');
    }

    public function edit(GameMatch $match): View
    {
        return view('admin.matches.form', $this->formData($match));
    }

    /**
     * Handles both the fixture details AND live score/status updates —
     * a real deployment would separate these once the Fixture/Live
     * Scores API (Module 7) drives status/score automatically, but a
     * manual override path is essential here since that integration
     * doesn't exist yet.
     */
    public function update(Request $request, GameMatch $match): RedirectResponse
    {
        $match->update($this->validated($request));

        return redirect()->route('admin.matches.index')->with('status', 'Match updated.');
    }

    public function destroy(GameMatch $match): RedirectResponse
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('status', 'Match deleted.');
    }

    private function formData(GameMatch $match): array
    {
        return [
            'match' => $match,
            'sports' => Sport::orderBy('name')->get(),
            'leagues' => League::orderBy('name')->get(),
            'teams' => Team::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
            'league_id' => ['required', 'integer', 'exists:leagues,id'],
            'home_team_id' => ['required', 'integer', 'exists:teams,id', 'different:away_team_id'],
            'away_team_id' => ['required', 'integer', 'exists:teams,id'],
            'kickoff_at' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:scheduled,live,finished,postponed,cancelled'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
            'minute' => ['nullable', 'integer', 'min:0', 'max:130'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
