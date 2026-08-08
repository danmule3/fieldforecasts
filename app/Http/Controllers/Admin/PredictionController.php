<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettlePredictionRequest;
use App\Http\Requests\StorePredictionRequest;
use App\Http\Requests\UpdatePredictionRequest;
use App\Models\GameMatch;
use App\Models\Market;
use App\Models\Prediction;
use App\Services\PredictionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PredictionController extends AdminController
{
    public function __construct(private readonly PredictionService $predictions)
    {
    }

    public function index(Request $request): View
    {
        $predictions = Prediction::with(['match.homeTeam', 'match.awayTeam', 'market'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.predictions.index', ['predictions' => $predictions]);
    }

    public function create(): View
    {
        return view('admin.predictions.form', $this->formData(new Prediction()));
    }

    public function store(StorePredictionRequest $request): RedirectResponse
    {
        $this->predictions->create($request->validated(), $request->user());

        return redirect()->route('admin.predictions.index')->with('status', 'Prediction created.');
    }

    public function edit(Prediction $prediction): View
    {
        return view('admin.predictions.form', $this->formData($prediction));
    }

    public function update(UpdatePredictionRequest $request, Prediction $prediction): RedirectResponse
    {
        $this->predictions->update($prediction, $request->validated(), $request->user());

        return redirect()->route('admin.predictions.index')->with('status', 'Prediction updated.');
    }

    /** Restricted to Administrator+ via PredictionPolicy::settle() — see SettlePredictionRequest::authorize(). */
    public function settle(SettlePredictionRequest $request, Prediction $prediction): RedirectResponse
    {
        $this->predictions->settle($prediction, $request->validated('outcome'), $request->user(), $request->validated('notes'));

        return back()->with('status', 'Prediction settled.');
    }

    public function destroy(Prediction $prediction): RedirectResponse
    {
        $this->authorize('delete', $prediction);
        $prediction->delete();

        return redirect()->route('admin.predictions.index')->with('status', 'Prediction deleted.');
    }

    private function formData(Prediction $prediction): array
    {
        return [
            'prediction' => $prediction,
            'matches' => GameMatch::with(['homeTeam', 'awayTeam'])->orderByDesc('kickoff_at')->limit(200)->get(),
            'markets' => Market::orderBy('display_order')->get(),
        ];
    }
}
