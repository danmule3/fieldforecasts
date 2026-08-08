<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

require __DIR__ . '/sports.php';
require __DIR__ . '/predictions.php';
require __DIR__ . '/subscriptions.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/seo.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin panel routes are registered from Module 5 onward via
// routes/admin.php, loaded in RouteServiceProvider behind the
// 'auth' + 'access-admin-panel' gate/middleware stack.

// cms.php MUST be required last — it ends with a catch-all
// `/{page:slug}` route (static pages like About/Privacy Policy) that
// would otherwise swallow every route registered after it, including
// /login, /register, /dashboard, and /profile. Learned this the hard
// way — see the comment at the top of cms.php.
require __DIR__ . '/cms.php';
