<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Only Weekly and Monthly billing periods, per the brief ("No Annual
     * Plan"). `duration_days` (rather than deriving it from
     * billing_period in code) lets an admin fine-tune a plan's actual
     * access window independently of its marketing name.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Weekly Premium", "Monthly Premium"
            $table->string('slug')->unique();
            $table->enum('billing_period', ['weekly', 'monthly']);
            $table->unsignedSmallInteger('duration_days'); // 7 for weekly, 30 for monthly (admin-adjustable)
            $table->unsignedInteger('price_cents');
            $table->string('currency', 3)->default('USD');
            $table->json('features')->nullable(); // bullet list shown on the plans page
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
