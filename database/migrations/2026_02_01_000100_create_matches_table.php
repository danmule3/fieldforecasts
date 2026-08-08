<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fixture/match data. Deliberately holds only match facts (teams,
     * kickoff, score, status) — predictions, odds, and analysis live in
     * their own tables (Module 3) with a belongsTo back to this table,
     * so a single match can carry multiple predictions/markets without
     * denormalizing this table.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();

            $table->timestamp('kickoff_at');
            $table->string('venue')->nullable();

            // scheduled -> live -> finished, or postponed/cancelled at any point
            $table->enum('status', ['scheduled', 'live', 'finished', 'postponed', 'cancelled'])
                ->default('scheduled');

            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->unsignedSmallInteger('minute')->nullable(); // live match clock

            $table->boolean('is_featured')->default(false);

            $table->string('external_provider')->nullable();
            $table->string('external_ref')->nullable();

            $table->timestamps();

            $table->index(['kickoff_at', 'status']);
            $table->index(['league_id', 'kickoff_at']);
            $table->index(['is_featured', 'kickoff_at']);
            $table->unique(['external_provider', 'external_ref']);
        });

        // Powers the "Favourite Teams" dashboard widget reserved in Module 1.
        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('matches');
    }
};
