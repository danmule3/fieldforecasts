<?php

namespace App\Console\Commands;

use App\Services\ApiKeyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Diagnostic-only command — hits SportSRC directly (bypassing the
 * AbstractApiProvider cache/rate-limit wrapper on purpose, so this
 * always gives a fresh live response) and dumps the raw JSON so the
 * exact field names in SportSrcProvider's map*() methods can be
 * verified/corrected against real data instead of guessed.
 */
class TestSportsApiFetch extends Command
{
    protected $signature = 'sports-api:test-fetch {type=matches : matches|detail|odds|standing|stats|account|sports} {--id=} {--sport=football} {--status=} {--date=} {--league_id=}';

    protected $description = 'Fetch a raw response from SportSRC and dump it — for verifying response field names during setup.';

    public function handle(ApiKeyService $apiKeys): int
    {
        $config = config('sports_api.providers.sportsrc');
        $key = $apiKeys->getActiveKeyValue('sportsrc');

        if (! $key) {
            $this->error('No active API key found for provider [sportsrc]. Add one in Admin > API Keys first.');
            return self::FAILURE;
        }

        $query = array_filter([
            'type' => $this->argument('type'),
            'id' => $this->option('id'),
            'sport' => $this->option('sport'),
            'status' => $this->option('status'),
            'date' => $this->option('date'),
            'league_id' => $this->option('league_id'),
        ]);

        $this->info('Requesting: ' . rtrim($config['base_url'], '/') . '/?' . http_build_query($query));

        $response = Http::withHeaders(['X-API-KEY' => $key])
            ->timeout($config['timeout'])
            ->get(rtrim($config['base_url'], '/') . '/', $query);

        $this->newLine();
        $this->line('HTTP status: ' . $response->status());
        $this->newLine();
        $this->line(json_encode($response->json() ?? $response->body(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return self::SUCCESS;
    }
}
