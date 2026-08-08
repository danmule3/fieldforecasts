<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchFilterRequest;
use App\Models\GameMatch;
use App\Models\Sport;
use App\Repositories\Contracts\OddsRepositoryInterface;
use App\Services\MatchService;
use App\Services\PredictionService;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function __construct(
        private readonly MatchService $matchService,
        private readonly PredictionService $predictionService,
        private readonly OddsRepositoryInterface $oddsRepository,
    ) {
    }

    /**
     * Browse/search matches with sport/league/status/date filters.
     * Validated query params keep the underlying query bounded — no
     * unindexed free-text filtering against the matches table.
     */
    public function index(MatchFilterRequest $request): View
    {
        $filters = $request->validated();

        if (! empty($filters['sport'])) {
            $filters['sport_id'] = Sport::where('slug', $filters['sport'])->value('id');
        }

        return view('matches.index', [
            'matches' => $this->matchService->filtered($filters),
            'sports' => Sport::where('is_active', true)->orderBy('display_order')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(GameMatch $match): View
    {
        $match->load(['sport', 'league', 'homeTeam', 'awayTeam']);

        return view('matches.show', [
            'match' => $match,
            'predictions' => $this->predictionService->forMatch($match->id),
            'oddsByMarket' => $this->oddsRepository->forMatch($match),
        ]);
    }
}
