<?php

use App\Http\Controllers\LeagueController;
use App\Http\Controllers\LiveMatchesController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

// SEO-friendly, human-readable URL structure throughout — see Module 8
// for meta tags/schema.org; slugs here are the foundation for that.

Route::get('/sports', [SportController::class, 'index'])->name('sports.index');
Route::get('/sports/{sport:slug}', [SportController::class, 'show'])->name('sports.show');

Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');
Route::get('/live-matches-partial', LiveMatchesController::class)->name('live-matches.partial');

Route::get('/leagues/{league:slug}', [LeagueController::class, 'show'])->name('leagues.show');

Route::get('/teams/{team:slug}', [TeamController::class, 'show'])->name('teams.show');
Route::post('/teams/{team:slug}/follow', [TeamController::class, 'toggleFollow'])
    ->middleware('auth')
    ->name('teams.follow');
