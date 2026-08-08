<?php

namespace App\Repositories\Eloquent;

use App\Models\GameMatch;
use App\Models\Sport;
use App\Repositories\Contracts\MatchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EloquentMatchRepository implements MatchRepositoryInterface
{
    /** Base eager-loads shared by every read method to avoid N+1s in match cards. */
    private const WITH = ['sport', 'league', 'homeTeam', 'awayTeam'];

    public function today(?Sport $sport = null): Collection
    {
        $cacheKey = 'matches:today:' . ($sport?->id ?? 'all') . ':' . now()->format('Y-m-d-H');

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($sport) {
            return GameMatch::query()
                ->with(self::WITH)
                ->today()
                ->when($sport, fn ($q) => $q->where('sport_id', $sport->id))
                ->orderBy('kickoff_at')
                ->get();
        });
    }

    public function live(?Sport $sport = null): Collection
    {
        // Short TTL — live scores change frequently; this cache mainly
        // absorbs traffic bursts between polling intervals, not a
        // substitute for the real-time push Module 7 will add.
        $cacheKey = 'matches:live:' . ($sport?->id ?? 'all');

        return Cache::remember($cacheKey, now()->addSeconds(30), function () use ($sport) {
            return GameMatch::query()
                ->with(self::WITH)
                ->live()
                ->when($sport, fn ($q) => $q->where('sport_id', $sport->id))
                ->orderBy('kickoff_at')
                ->get();
        });
    }

    public function upcoming(?Sport $sport = null, int $limit = 10): Collection
    {
        return GameMatch::query()
            ->with(self::WITH)
            ->upcoming()
            ->when($sport, fn ($q) => $q->where('sport_id', $sport->id))
            ->orderBy('kickoff_at')
            ->limit($limit)
            ->get();
    }

    public function featured(int $limit = 5): Collection
    {
        return GameMatch::query()
            ->with(self::WITH)
            ->where('is_featured', true)
            ->whereIn('status', [GameMatch::STATUS_SCHEDULED, GameMatch::STATUS_LIVE])
            ->orderBy('kickoff_at')
            ->limit($limit)
            ->get();
    }

    public function paginateForLeague(int $leagueId, int $perPage = 20): LengthAwarePaginator
    {
        return GameMatch::query()
            ->with(self::WITH)
            ->where('league_id', $leagueId)
            ->orderByDesc('kickoff_at')
            ->paginate($perPage);
    }

    public function paginateFiltered(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return GameMatch::query()
            ->with(self::WITH)
            ->when($filters['sport_id'] ?? null, fn ($q, $v) => $q->where('sport_id', $v))
            ->when($filters['league_id'] ?? null, fn ($q, $v) => $q->where('league_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['date'] ?? null, fn ($q, $v) => $q->whereDate('kickoff_at', $v))
            ->orderBy('kickoff_at')
            ->paginate($perPage);
    }
}
