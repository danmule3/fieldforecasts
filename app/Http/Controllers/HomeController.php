<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use App\Models\Slide;
use App\Models\Sport;
use App\Models\Testimonial;
use App\Services\ArticleService;
use App\Services\MatchService;
use App\Services\PredictionService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly MatchService $matchService,
        private readonly PredictionService $predictionService,
        private readonly ArticleService $articleService,
    ) {
    }

    public function __invoke(): View
    {
        return view('welcome', [
            'pageSections' => PageSection::forPage('home')->visible()->ordered()->get(),
            'sections' => $this->matchService->homepageSections(),
            'sports' => Sport::where('is_active', true)->orderBy('display_order')->get(),
            'todaysPredictions' => $this->predictionService->todaysPredictions(6),
            'recentWinners' => $this->predictionService->recentWinners(6),
            'accuracy' => $this->predictionService->accuracyPercentage(),
            'latestArticles' => $this->articleService->latest(3),
            'testimonials' => Testimonial::active()->limit(6)->get(),
            'heroSlides' => Slide::forPlacement(Slide::PLACEMENT_HOMEPAGE_HERO)->get(),
        ]);
    }
}
