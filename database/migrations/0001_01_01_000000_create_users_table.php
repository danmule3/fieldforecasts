<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Core users table.
     *
     * Notes:
     * - Role is NOT stored here; roles/permissions are handled by Spatie
     *   laravel-permission (model_has_roles pivot) so a user can carry
     *   multiple roles and permissions can be granted granularly.
     * - `is_premium` + `premium_expires_at` are denormalized "fast path"
     *   fields used by the PremiumAccess gate/middleware to avoid a join
     *   on every request; the source of truth remains the subscriptions
     *   table (added in Module 4).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar_path')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('locale', 5)->default('en');

            // Fast-path premium flags (denormalized, see note above)
            $table->boolean('is_premium')->default(false);
            $table->timestamp('premium_expires_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_premium', 'premium_expires_at']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
