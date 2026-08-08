<?php

namespace App\Services;

use App\Events\PredictionSettled;
use App\Models\Prediction;
use App\Models\PredictionResult;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PredictionService
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    public function create(array $data, User $author): Prediction
    {
        return DB::transaction(function () use ($data, $author) {
            $prediction = Prediction::create([
                ...$data,
                'author_id' => $author->id,
                'published_at' => $data['published_at'] ?? now(),
            ]);

            $this->activityLogger->log('prediction.created', $author, $prediction);
            $this->forgetAccuracyCache();

            return $prediction;
        });
    }

    public function update(Prediction $prediction, array $data, User $editor): Prediction
    {
        $prediction->update($data);
        $this->activityLogger->log('prediction.updated', $editor, $prediction);

        return $prediction;
    }

    /**
     * Settle a prediction's outcome. This is the ONLY code path allowed
     * to write to prediction_results / trigger the status sync — see
     * migration/listener comments. Wrapped in a transaction so the
     * result row and the fast-path status update (via the listener)
     * never disagree.
     */
    public function settle(Prediction $prediction, string $outcome, User $settledBy, ?string $notes = null): PredictionResult
    {
        return DB::transaction(function () use ($prediction, $outcome, $settledBy, $notes) {
            $result = PredictionResult::create([
                'prediction_id' => $prediction->id,
                'outcome' => $outcome,
                'settled_by' => $settledBy->id,
                'notes' => $notes,
                'settled_at' => now(),
            ]);

            event(new PredictionSettled($prediction, $result));

            $this->activityLogger->log('prediction.settled', $settledBy, $prediction, ['outcome' => $outcome]);
            $this->forgetAccuracyCache();

            return $result;
        });
    }

    public function toggleSave(Prediction $prediction, User $user): bool
    {
        $isSaved = $user->savedPredictions()->where('prediction_id', $prediction->id)->exists();

        $isSaved
            ? $user->savedPredictions()->detach($prediction->id)
            : $user->savedPredictions()->attach($prediction->id);

        return ! $isSaved;
    }

    public function recentWinners(int $limit = 6): Collection
    {
        return Prediction::query()
            ->with(['match.homeTeam', 'match.awayTeam', 'market'])
            ->where('status', Prediction::STATUS_WON)
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function todaysPredictions(int $limit = 12): Collection
    {
        return Prediction::query()
            ->with(['match.homeTeam', 'match.awayTeam', 'market'])
            ->published()
            ->whereHas('match', fn ($q) => $q->today())
            ->orderByDesc('is_premium')
            ->orderByDesc('confidence')
            ->limit($limit)
            ->get();
    }

    public function forMatch(int $matchId): Collection
    {
        return Prediction::query()
            ->with(['market', 'author'])
            ->where('match_id', $matchId)
            ->published()
            ->orderByDesc('confidence')
            ->get();
    }

    public function paginateFiltered(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return Prediction::query()
            ->with(['match.homeTeam', 'match.awayTeam', 'market'])
            ->published()
            ->when($filters['is_premium'] ?? null, fn ($q, $v) => $q->where('is_premium', $v === 'free' ? false : true))
            ->when($filters['sport_id'] ?? null, fn ($q, $v) => $q->whereHas('match', fn ($m) => $m->where('sport_id', $v)))
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }

    /**
     * Win rate across all settled predictions. Cached for a few minutes
     * since it's a full-table aggregate and the homepage stat doesn't
     * need second-by-second freshness.
     */
    public function accuracyPercentage(): float
    {
        return Cache::remember('predictions:accuracy', now()->addMinutes(10), function () {
            $won = Prediction::query()->where('status', Prediction::STATUS_WON)->count();
            $lost = Prediction::query()->where('status', Prediction::STATUS_LOST)->count();
            $settled = $won + $lost;

            return $settled > 0 ? round(($won / $settled) * 100, 1) : 0.0;
        });
    }

    private function forgetAccuracyCache(): void
    {
        Cache::forget('predictions:accuracy');
    }
}
