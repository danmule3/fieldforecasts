<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Cache;

class ApiKeyService
{
    public function getActiveKeyValue(string $provider): ?string
    {
        $key = $this->getActiveKey($provider);

        return $key?->key_value;
    }

    public function hasActiveKey(string $provider): bool
    {
        return Cache::remember(
            "api-keys:has-active:{$provider}",
            now()->addMinutes(5),
            fn () => ApiKey::activeForProvider($provider)->exists()
        );
    }

    public function getActiveKey(string $provider): ?ApiKey
    {
        return ApiKey::activeForProvider($provider)->latest('id')->first();
    }

    public function markUsed(ApiKey $key): void
    {
        $key->update(['last_used_at' => now()]);
    }

    public function forgetCache(string $provider): void
    {
        Cache::forget("api-keys:has-active:{$provider}");
    }
}
