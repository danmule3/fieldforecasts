<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Database indexing review (brief: "Database Indexing" under
     * Performance). Every foreign key added since Module 1 already
     * gets an index automatically from `constrained()`; this migration
     * only adds the handful of *additional* indexes for columns that
     * are filtered/sorted on directly but weren't covered by an
     * existing composite index — found by reviewing every `where()`/
     * `orderBy()` call across the controllers and services built in
     * Modules 1–7.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('predictions', function (Blueprint $table) {
            $table->index('author_id');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index('author_id');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('comments', fn (Blueprint $table) => $table->dropIndex(['user_id']));
        Schema::table('predictions', fn (Blueprint $table) => $table->dropIndex(['author_id']));
        Schema::table('articles', fn (Blueprint $table) => $table->dropIndex(['author_id']));
        Schema::table('activity_logs', fn (Blueprint $table) => $table->dropIndex(['user_id']));
    }
};
