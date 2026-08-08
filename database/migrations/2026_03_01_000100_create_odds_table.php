<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Odds are informational display only — never a betting slip. Each
     * row is one selection within one market for one match (e.g.
     * match #4, market "1x2", selection "Home", price 1.85).
     * `external_ref`/`fetched_at` support the live Odds API integration
     * (Module 7); rows without them are manually entered by Editors.
     */
    public function up(): void
    {
        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->string('selection'); // "Home", "Draw", "Away", "Over", "Under", "Yes", "No", "2-1", etc.
            $table->decimal('price', 6, 2); // decimal odds, e.g. 1.85 — display only
            $table->string('provider')->default('manual'); // "manual" or an API provider key
            $table->string('external_ref')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            $table->index(['match_id', 'market_id']);
            $table->unique(['match_id', 'market_id', 'selection', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odds');
    }
};
