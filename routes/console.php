<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Daily subscription lifecycle sweep: expires lapsed access windows
 * and sends renewal reminders. `withoutOverlapping` guards against a
 * slow run still executing when the next day's schedule tick fires.
 */
Schedule::command('subscriptions:process')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->onOneServer();

/**
 * External sports-data sync cadence. All four are no-ops (log a line,
 * exit clean) if no active API key is configured — see
 * SyncSportsData::handle() — so enabling this schedule is always safe
 * even before a provider is contracted.
 */
Schedule::command('sports-api:sync fixtures')->dailyAt('03:00')->withoutOverlapping()->onOneServer();
Schedule::command('sports-api:sync standings')->hourly()->withoutOverlapping()->onOneServer();
Schedule::command('sports-api:sync odds')->everyFifteenMinutes()->withoutOverlapping()->onOneServer();
Schedule::command('sports-api:sync live-scores')->everyMinute()->withoutOverlapping()->onOneServer();
