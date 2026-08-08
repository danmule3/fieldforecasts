<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Taxonomy tables. All four (sports, countries, leagues, teams) are
     * intentionally provider-agnostic: `external_ref` columns hold the
     * ID assigned by whichever fixture/odds API is wired in later
     * (Module 7), scoped by `external_provider`, so switching providers
     * or running two in parallel never requires a schema change.
     */
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // icon key/class, e.g. "football"
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('iso_code', 3)->nullable()->unique();
            $table->string('flag_path')->nullable();
            $table->timestamps();
        });

        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('season')->nullable(); // e.g. "2026/2027"
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('external_provider')->nullable();
            $table->string('external_ref')->nullable();
            $table->timestamps();

            $table->index(['sport_id', 'is_active']);
            $table->unique(['external_provider', 'external_ref']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('external_provider')->nullable();
            $table->string('external_ref')->nullable();
            $table->timestamps();

            $table->index(['sport_id']);
            $table->unique(['external_provider', 'external_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
        Schema::dropIfExists('leagues');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('sports');
    }
};
