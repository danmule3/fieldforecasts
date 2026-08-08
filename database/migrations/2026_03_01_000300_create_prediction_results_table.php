<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only settlement history — kept distinct from
     * `predictions.status` (the fast-path current status) so a
     * corrected/re-settled prediction retains a full audit trail of
     * who settled it, when, and why, rather than silently overwriting
     * the previous outcome.
     */
    public function up(): void
    {
        Schema::create('prediction_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained()->cascadeOnDelete();
            $table->enum('outcome', ['won', 'lost', 'cancelled'])->comment('final settled outcome');
            $table->foreignId('settled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('settled_at');
            $table->timestamps();

            $table->index(['prediction_id', 'settled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_results');
    }
};
