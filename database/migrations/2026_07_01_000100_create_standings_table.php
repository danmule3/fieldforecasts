<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per team per league per season. `external_provider`/
     * `external_ref` follow the same pattern as leagues/teams/matches
     * so the Standings API sync job can upsert idempotently; rows can
     * also be entered manually by an Editor before that integration
     * is live, same as odds.
     */
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('season')->nullable();

            $table->unsignedSmallInteger('position')->default(0);
            $table->unsignedSmallInteger('played')->default(0);
            $table->unsignedSmallInteger('won')->default(0);
            $table->unsignedSmallInteger('drawn')->default(0);
            $table->unsignedSmallInteger('lost')->default(0);
            $table->smallInteger('goals_for')->default(0);
            $table->smallInteger('goals_against')->default(0);
            $table->smallInteger('points')->default(0);

            $table->string('external_provider')->nullable();
            $table->string('external_ref')->nullable();

            $table->timestamps();

            $table->unique(['league_id', 'team_id', 'season']);
            $table->index(['league_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
