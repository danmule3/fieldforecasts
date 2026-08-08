<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Services\PredictionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedPredictionController extends Controller
{
    public function __construct(private readonly PredictionService $predictions)
    {
    }

    public function toggle(Request $request, Prediction $prediction): RedirectResponse
    {
        $this->predictions->toggleSave($prediction, $request->user());

        return back();
    }
}
