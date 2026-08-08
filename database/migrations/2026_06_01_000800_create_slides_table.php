<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The brief lists "Manage Banners" and "Manage Sliders" as separate
     * admin items, but both are the same underlying shape: an image,
     * optional link, and a display position. Rather than two near-
     * identical tables/controllers, this is one `slides` table with a
     * `placement` key ("homepage_hero" for the rotating slider,
     * "homepage_banner" for a static banner strip, etc.) — new
     * placements are just a new key, not a new table.
     */
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('placement')->default('homepage_hero');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['placement', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
