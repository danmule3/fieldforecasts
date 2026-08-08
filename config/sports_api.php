<?php

/**
 * Configuration for external sports-data providers (Fixture, Odds,
 * Standings, Live Scores, Statistics APIs — see brief's LIVE API
 * section). Base URLs and behavior knobs live here, never inline in
 * a Service or Provider class, so switching vendors or environments
 * is a config/env change, not a code change.
 *
 * Currently configured for SportSRC v2 (https://sportsrc.org/v2/):
 * a single base URL with a `type=` query parameter selecting the
 * resource (matches, detail, odds, standing, stats, etc.), auth via
 * an `X-API-KEY` header. This is a small, non-mainstream provider
 * whose own terms describe it as a data-aggregation/scraping service
 * with no accuracy guarantees — treat it as best-effort, not a
 * system of record, and keep the provider abstraction (Contracts/)
 * so swapping to a licensed vendor later is a config + adapter
 * change, not a rewrite.
 *
 * `default` selects which provider key the app resolves interfaces
 * to at runtime (see SportsApiServiceProvider). Falls back to the
 * Null provider automatically if no active ApiKey exists for it.
 */
return [
    'default' => env('SPORTS_API_PROVIDER', 'sportsrc'),

    'providers' => [
        'sportsrc' => [
            'base_url' => env('SPORTS_API_BASE_URL', 'https://api.sportsrc.org/v2/'),
            'timeout' => (int) env('SPORTS_API_TIMEOUT', 10),
            'retry_times' => (int) env('SPORTS_API_RETRY_TIMES', 3),
            'retry_delay_ms' => (int) env('SPORTS_API_RETRY_DELAY_MS', 200),
            // Free tier is 1,000 requests/DAY (not per-minute) — this
            // per-minute cap is deliberately conservative so a burst of
            // polling during live matches doesn't blow the daily quota
            // by mid-afternoon. Raise it if you're on a paid plan
            // (Starter=10k/day, Pro=25k/day, Business=100k/day) and
            // want tighter live-score polling.
            'rate_limit_per_minute' => (int) env('SPORTS_API_RATE_LIMIT', 15),
            'cache_ttl_seconds' => (int) env('SPORTS_API_CACHE_TTL', 30),
        ],
    ],
];
