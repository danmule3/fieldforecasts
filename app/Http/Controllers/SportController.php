<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Services\MatchService;
use Illuminate\View\View;

class SportController extends Controller
{
    public function __construct(private readonly MatchService $matchService)
    {
    }

    public function index(): View
    {
        return view('sports.index', [
            'sports' => Sport::where('is_active', true)->orderBy('display_order')->withCount('matches')->get(),
        ]);
    }

    public function show(Sport $sport): View
    {
        return view('sports.show', [
            'sport' => $sport,
            'leagues' => $sport->leagues()->where('is_active', true)->orderByDesc('is_featured')->get(),
            'sections' => $this->matchService->homepageSections($sport),
        ]);
    }
}
