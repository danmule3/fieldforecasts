<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Payment;
use App\Models\Prediction;
use App\Models\Sport;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Aggregates the "Analytics" section from the brief that's derivable
 * from data this platform already has. "Daily Visitors" is explicitly
 * NOT included — that needs real traffic tracking (Module 8's SEO/
 * analytics layer, or a third-party tool like Plausible/GA), not
 * something to fake with a placeholder number here.
 */
class AnalyticsService
{
    public function summary(): array
    {
        return Cache::remember('admin:analytics:summary', now()->addMinutes(5), function () {
            return [
                'registered_users' => User::count(),
                'premium_users' => User::where('is_premium', true)->count(),
                'total_predictions' => Prediction::count(),
                'settled_predictions' => Prediction::settled()->count(),
                'revenue_cents' => Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount_cents'),
                'active_subscriptions' => Subscription::active()->count(),
                'subscriptions_this_month' => Subscription::where('created_at', '>=', now()->startOfMonth())->count(),
                'subscriptions_last_month' => Subscription::whereBetween('created_at', [
                    now()->subMonthNoOverflow()->startOfMonth(),
                    now()->subMonthNoOverflow()->endOfMonth(),
                ])->count(),
            ];
        });
    }

    public function mostViewedPredictions(int $limit = 5)
    {
        return Prediction::query()
            ->with(['match.homeTeam', 'match.awayTeam'])
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function popularSports(int $limit = 5)
    {
        return Sport::query()
            ->withCount('matches')
            ->orderByDesc('matches_count')
            ->limit($limit)
            ->get();
    }

    public function recentActivity(int $limit = 15)
    {
        return \App\Models\ActivityLog::query()
            ->with('user')
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
