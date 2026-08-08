<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `status` here is a fast-path denormalized field (mirrors the
     * settlement recorded in prediction_results) so listing/filtering
     * "Recent Winners" or computing accuracy never needs a join —
     * it's kept in sync by the PredictionSettled event listener,
     * never written directly outside of PredictionService::settle().
     */
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->restrictOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('pick'); // the predicted selection, e.g. "Home", "Over 2.5"
            $table->decimal('odds_at_publish', 6, 2)->nullable(); // snapshot, since odds move
            $table->unsignedTinyInteger('confidence'); // 0-100

            $table->text('analysis');
            $table->text('reasoning')->nullable();
            $table->text('recent_form_summary')->nullable();
            $table->text('head_to_head_summary')->nullable();
            $table->text('injury_notes')->nullable();

            $table->boolean('is_premium')->default(false);
            $table->enum('status', ['pending', 'won', 'lost', 'cancelled'])->default('pending');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['match_id']);
            $table->index(['is_premium', 'published_at']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
