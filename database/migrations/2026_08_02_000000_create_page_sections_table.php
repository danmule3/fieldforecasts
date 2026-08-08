<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drives the admin "Page Sections" builder — each row is one block
     * on a page (currently only `page = 'home'` is used, but the
     * column exists so other pages can adopt the same system later
     * without a schema change).
     *
     * `content` is JSON rather than fixed columns because section
     * types are structurally different (a Hero needs
     * headline/subheadline; a Features section needs a list of
     * icon/title/text items) — one flexible column here is simpler
     * than a table per type. `section_key` is a stable machine name
     * (e.g. "hero", "stats") used by the Blade partial resolver;
     * `type` selects which partial renders it.
     */
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('home');
            $table->string('section_key');
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();

            $table->unique(['page', 'section_key']);
            $table->index(['page', 'is_visible', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
