<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\MatchService;
use Illuminate\View\View;

class LeagueController extends Controller
{
    public function __construct(private readonly MatchService $matchService)
    {
    }

    public function show(League $league): View
    {
        $league->load(['sport', 'country']);

        return view('leagues.show', [
            'league' => $league,
            'matches' => $this->matchService->forLeague($league->id),
            'standings' => $league->standings()->with('team')->get(),
        ]);
    }
}
