<?php

namespace App\Jobs;

use App\Models\GameMatch;
use App\Services\ExternalApi\Contracts\LiveScoreProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

/**
 * Runs frequently (every minute — see routes/console.php), so it's
 * intentionally NOT per-match: one provider call covers every live
 * fixture, matched back to local rows by external_ref.
 */
class SyncLiveScoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 15;

    public function handle(LiveScoreProviderInterface $provider): void
    {
        $updates = $provider->fetchLiveScores();

        if ($updates->isEmpty()) {
            return;
        }

        $matchesByRef = GameMatch::where('external_provider', 'sportsrc')
            ->whereIn('external_ref', $updates->pluck('external_ref'))
            ->get()
            ->keyBy('external_ref');

        foreach ($updates as $update) {
            $match = $matchesByRef->get($update['external_ref']);

            if (! $match) {
                continue;
            }

            $match->update([
                'home_score' => $update['home_score'] ?? $match->home_score,
                'away_score' => $update['away_score'] ?? $match->away_score,
                'minute' => $update['minute'] ?? $match->minute,
                'status' => $update['status'] ?? $match->status,
            ]);
        }

        // Live match list is cached (Module 2's EloquentMatchRepository,
        // 30s TTL) — force it fresh immediately after a score update
        // rather than waiting out the TTL, so the homepage reflects
        // this cycle's scores right away.
        Cache::forget('matches:live:all');
    }
}
