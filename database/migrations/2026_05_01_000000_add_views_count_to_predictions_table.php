<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Backs the "Most Viewed Predictions" analytics widget — a plain
     * incrementing counter rather than a full pageview-log table, which
     * would be overkill until real traffic analytics (Module 8) exist.
     */
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->unsignedInteger('views_count')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });
    }
};
