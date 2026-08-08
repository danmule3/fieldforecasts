<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Powers the "Saved Predictions" user dashboard widget from Module 1. */
    public function up(): void
    {
        Schema::create('prediction_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prediction_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'prediction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_user');
    }
};
