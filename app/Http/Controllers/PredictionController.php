<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchFilterRequest;
use App\Models\Prediction;
use App\Models\Sport;
use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PredictionController extends Controller
{
    public function __construct(private readonly PredictionService $predictions)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['is_premium', 'sport_id']);

        return view('predictions.index', [
            'predictions' => $this->predictions->paginateFiltered($filters),
            'sports' => Sport::where('is_active', true)->orderBy('display_order')->get(),
            'accuracy' => $this->predictions->accuracyPercentage(),
        ]);
    }

    /**
     * Premium predictions are shown as a blurred preview to non-premium
     * users (`can('view', ...)` gate) rather than a 403 — matches the
     * brief's "Premium Predictions Preview" homepage/browse behavior:
     * discoverable, not hidden, but content withheld until subscribed.
     */
    public function show(Prediction $prediction): View
    {
        $prediction->increment('views_count');
        $prediction->load(['match.homeTeam', 'match.awayTeam', 'match.league', 'market', 'author']);

        $canViewFull = auth()->user()?->can('view', $prediction) ?? ! $prediction->is_premium;

        $isSaved = auth()->check()
            ? $prediction->savedBy()->where('user_id', auth()->id())->exists()
            : false;

        return view('predictions.show', compact('prediction', 'canViewFull', 'isSaved'));
    }
}
