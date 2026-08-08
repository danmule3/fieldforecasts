<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Raw statistics blob (possession, shots, corners, cards, etc.)
     * from the Statistics API. Kept as JSON rather than a rigid
     * column-per-stat schema because different sports/providers
     * expose very different stat sets — football and cricket have
     * almost nothing in common here — and this column is populated
     * and read as a unit, never queried/filtered by individual stat.
     */
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->json('statistics')->nullable()->after('minute');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('statistics');
        });
    }
};
