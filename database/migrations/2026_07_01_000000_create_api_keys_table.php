<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `key_value` is stored via Laravel's `encrypted` cast (see ApiKey
     * model) — encrypted at rest using APP_KEY, decrypted only when
     * actually making a request. Never logged, never rendered in full
     * in the admin UI (masked to the last 4 characters).
     */
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // e.g. "sportsrc" — matches config/sports_api.php keys
            $table->string('label'); // human-readable, e.g. "Primary fixtures key"
            $table->text('key_value');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
