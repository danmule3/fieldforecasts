<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\Market;
use App\Models\Odd;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Database\Seeder;

class PredictionsDemoSeeder extends Seeder
{
    /**
     * Attaches a 1X2 odds line and a prediction to every demo match
     * created by SportsDemoDataSeeder, and settles the finished ones,
     * so the homepage's Recent Winners / Prediction Accuracy sections
     * and the premium blur preview have something real to render.
     */
    public function run(): void
    {
        $oneXTwo = Market::where('key', '1x2')->firstOrFail();
        $editor = User::role(User::ROLE_SUPER_ADMIN)->first() ?? User::first();

        if (! $editor) {
            $this->command?->warn('No user found to author demo predictions — run AdminUserSeeder first.');
            return;
        }

        GameMatch::with(['homeTeam', 'awayTeam'])->get()->each(function (GameMatch $match, int $index) use ($oneXTwo, $editor) {
            Odd::updateOrCreate(
                ['match_id' => $match->id, 'market_id' => $oneXTwo->id, 'selection' => 'Home', 'provider' => 'manual'],
                ['price' => 1.85, 'fetched_at' => now()]
            );
            Odd::updateOrCreate(
                ['match_id' => $match->id, 'market_id' => $oneXTwo->id, 'selection' => 'Draw', 'provider' => 'manual'],
                ['price' => 3.40, 'fetched_at' => now()]
            );
            Odd::updateOrCreate(
                ['match_id' => $match->id, 'market_id' => $oneXTwo->id, 'selection' => 'Away', 'provider' => 'manual'],
                ['price' => 4.20, 'fetched_at' => now()]
            );

            $isFinished = $match->status === GameMatch::STATUS_FINISHED;
            $status = Prediction::STATUS_PENDING;

            if ($isFinished) {
                $status = $match->home_score > $match->away_score ? Prediction::STATUS_WON : Prediction::STATUS_LOST;
            }

            // Seed data sets `status` directly rather than going through
            // PredictionService::settle()/PredictionSettled — there's no
            // real audit trail or notification to generate for fixture
            // data. Real settlements must always go through the service.

            Prediction::firstOrCreate(
                ['match_id' => $match->id, 'market_id' => $oneXTwo->id],
                [
                    'author_id' => $editor->id,
                    'pick' => 'Home',
                    'odds_at_publish' => 1.85,
                    'confidence' => 60 + ($index * 3) % 35,
                    'analysis' => "{$match->homeTeam->name} have the edge at home against {$match->awayTeam->name} based on recent form and squad depth.",
                    'reasoning' => 'Home advantage plus a stronger attacking record over the last five matches.',
                    'recent_form_summary' => "{$match->homeTeam->name}: WWDLW · {$match->awayTeam->name}: LDWLL",
                    'head_to_head_summary' => 'Last 5 meetings: 3 home wins, 1 draw, 1 away win.',
                    'is_premium' => $index % 3 === 0,
                    'status' => $status,
                    'published_at' => now()->subHours($index),
                ]
            );
        });
    }
}
