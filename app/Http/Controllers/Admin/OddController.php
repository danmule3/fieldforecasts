<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameMatch;
use App\Models\Market;
use App\Repositories\Contracts\OddsRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Odds are managed per-match rather than as a standalone flat list —
 * an odds row is meaningless without its match/market context, so the
 * admin workflow is always "open a match, edit its odds board".
 */
class OddController extends AdminController
{
    public function __construct(private readonly OddsRepositoryInterface $oddsRepository)
    {
    }

    public function index(GameMatch $match): View
    {
        return view('admin.odds.index', [
            'match' => $match->load(['homeTeam', 'awayTeam']),
            'oddsByMarket' => $this->oddsRepository->forMatch($match),
            'markets' => Market::where('is_active', true)->orderBy('display_order')->get(),
        ]);
    }

    /**
     * Accepts a market plus up to 4 selection/price pairs in one form
     * submission — covers 1X2, Double Chance, Over/Under, BTTS, Draw No
     * Bet without needing a dynamic add-row UI for this admin release.
     */
    public function store(Request $request, GameMatch $match): RedirectResponse
    {
        $data = $request->validate([
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'selections' => ['required', 'array', 'min:1'],
            'selections.*.selection' => ['nullable', 'string', 'max:20'],
            'selections.*.price' => ['nullable', 'numeric', 'min:1'],
        ]);

        $selections = collect($data['selections'])
            ->filter(fn ($row) => filled($row['selection'] ?? null) && filled($row['price'] ?? null))
            ->values()
            ->all();

        if (empty($selections)) {
            return back()->withErrors(['selections' => 'Enter at least one selection and price.']);
        }

        $this->oddsRepository->upsertForMatch($match, $data['market_id'], $selections, 'manual');

        return redirect()->route('admin.odds.index', $match)->with('status', 'Odds updated.');
    }
}
