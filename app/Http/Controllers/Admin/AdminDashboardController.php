<?php

namespace App\Http\Controllers\Admin;

use App\Services\AnalyticsService;
use Illuminate\View\View;

class AdminDashboardController extends AdminController
{
    public function __construct(private readonly AnalyticsService $analytics)
    {
    }

    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->analytics->summary(),
            'mostViewed' => $this->analytics->mostViewedPredictions(),
            'popularSports' => $this->analytics->popularSports(),
            'recentActivity' => $this->analytics->recentActivity(),
        ]);
    }
}
