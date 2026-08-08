<?php

namespace App\Services\ExternalApi;

use App\Services\ApiKeyService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Base class for concrete sports-data provider adapters. Concrete
 * providers (e.g. SportSrcProvider) only implement the interface
 * methods and describe *which* endpoint/params to call via `request()`
 * — every cross-cutting concern (auth header, timeout, retry, rate
 * limiting, response caching, and graceful failure) lives here exactly
 * once, per the brief's "never hardcode API logic" / "use caching,
 * retry logic, rate limiting, graceful failure" requirements.
 */
abstract class AbstractApiProvider
{
    protected string $providerKey;

    public function __construct(protected readonly ApiKeyService $apiKeys)
    {
    }

    /**
     * Performs a rate-limited, retried, cached GET request. Returns
     * null on any failure (bad response, timeout, exhausted retries,
     * rate limit hit) rather than throwing — callers (sync jobs) are
     * expected to treat null as "skip this cycle, try again next run"
     * rather than a fatal error, so one flaky provider call never
     * takes down a scheduled sync.
     */
    protected function request(string $endpoint, array $query = []): ?array
    {
        $config = config("sports_api.providers.{$this->providerKey}");

        if (! $config) {
            Log::warning("No sports_api config for provider [{$this->providerKey}].");
            return null;
        }

        $cacheKey = 'sports_api:' . $this->providerKey . ':' . md5($endpoint . serialize($query));

        return Cache::remember($cacheKey, now()->addSeconds($config['cache_ttl_seconds']), function () use ($config, $endpoint, $query) {
            $rateLimitKey = "sports_api_calls:{$this->providerKey}";

            if (RateLimiter::tooManyAttempts($rateLimitKey, $config['rate_limit_per_minute'])) {
                Log::warning("Sports API rate limit reached for provider [{$this->providerKey}].");
                return null;
            }
            RateLimiter::hit($rateLimitKey, 60);

            $apiKey = $this->apiKeys->getActiveKey($this->providerKey);

            if (! $apiKey) {
                Log::info("No active API key configured for provider [{$this->providerKey}] — skipping call.");
                return null;
            }

            try {
                $response = $this->client($config, $apiKey->key_value)
                    ->get(rtrim($config['base_url'], '/') . '/' . ltrim($endpoint, '/'), $query);

                $this->apiKeys->markUsed($apiKey);

                return $this->handleResponse($response);
            } catch (\Throwable $e) {
                // Network errors, DNS failures, timeouts after all
                // retries — logged, never thrown, so a scheduled sync
                // job degrades gracefully instead of failing the queue.
                Log::error("Sports API request failed for provider [{$this->providerKey}]: {$e->getMessage()}");
                return null;
            }
        });
    }

    protected function client(array $config, string $apiKey): PendingRequest
    {
        return Http::withHeaders(['X-API-KEY' => $apiKey])
            ->timeout($config['timeout'])
            ->retry($config['retry_times'], $config['retry_delay_ms'], throw: false);
    }

    protected function handleResponse(Response $response): ?array
    {
        if ($response->failed()) {
            Log::warning("Sports API returned {$response->status()} for provider [{$this->providerKey}].");
            return null;
        }

        return $response->json();
    }
}
