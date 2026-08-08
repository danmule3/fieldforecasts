<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lookup table for betting-information markets (1X2, BTTS, Over/Under,
     * etc). A table rather than a hardcoded enum because the platform
     * needs to add/retire markets (per-sport applicability, admin-managed
     * per "Manage Odds" in the brief) without a migration each time.
     */
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->nullable()->constrained()->nullOnDelete(); // null = applies to all sports
            $table->string('key')->unique(); // e.g. "1x2", "btts", "over_2_5", "asian_handicap"
            $table->string('name'); // e.g. "1X2", "Both Teams to Score", "Over 2.5 Goals"
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
