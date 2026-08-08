<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * User dashboard shell for this module. Subscriptions/Favourites/
     * Saved Predictions widgets are wired in once those modules exist
     * (Module 4+); this view already reserves the layout regions for
     * them so later modules only add partials, not restructure this file.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $user->load(['favouriteTeams', 'savedPredictions.match.homeTeam', 'savedPredictions.match.awayTeam']);

        return view('dashboard', [
            'user' => $user,
            'currentSubscription' => $user->currentSubscription(),
        ]);
    }
}
