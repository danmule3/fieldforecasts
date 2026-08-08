<?php

namespace App\Providers;

use App\Repositories\Contracts\MatchRepositoryInterface;
use App\Repositories\Contracts\OddsRepositoryInterface;
use App\Repositories\Eloquent\EloquentMatchRepository;
use App\Repositories\Eloquent\EloquentOddsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Single place to swap the match/odds data sources later — e.g. to
     * decorators that layer a live external API on top of these
     * Eloquent implementations — without touching any consumer.
     */
    public array $bindings = [
        MatchRepositoryInterface::class => EloquentMatchRepository::class,
        OddsRepositoryInterface::class => EloquentOddsRepository::class,
    ];
}
