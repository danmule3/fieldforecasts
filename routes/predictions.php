<?php

use App\Http\Controllers\PredictionController;
use App\Http\Controllers\SavedPredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');
Route::get('/predictions/{prediction}', [PredictionController::class, 'show'])->name('predictions.show');

Route::post('/predictions/{prediction}/save', [SavedPredictionController::class, 'toggle'])
    ->middleware('auth')
    ->name('predictions.save');

// Editor/Admin create, update, and settle endpoints (StorePredictionRequest,
// UpdatePredictionRequest, SettlePredictionRequest, and PredictionPolicy are
// already in place) are wired into routes/admin.php in Module 5, alongside
// the rest of the admin panel's Manage Predictions screens.
