<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Http\Response;

/**
 * Powers client-side polling of the "Live now" section. Our
 * architecture is a server-rendered Blade app, not a React SPA with a
 * WebSocket push server — this is the pragmatic equivalent: the
 * browser re-fetches this endpoint every ~20s (see the Alpine
 * component on the homepage) and swaps in fresh HTML. Good enough for
 * "live" without needing infrastructure (Reverb/Pusher/a Node.js
 * server) this project was never built with.
 */
class LiveMatchesController extends Controller
{
    public function __invoke(MatchService $matchService): Response
    {
        $matches = $matchService->liveTicker();

        return response()->view('partials.live-matches', ['matches' => $matches]);
    }
}
