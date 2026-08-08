<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ApiKeyController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LeagueController;
use App\Http\Controllers\Admin\MarketController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OddController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\SportController;
use App\Http\Controllers\Admin\StandingController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'can:access-admin-panel'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Content — Editor+
        Route::resource('sports', SportController::class)->except('show');
        Route::resource('countries', CountryController::class)->except('show');
        Route::resource('markets', MarketController::class)->except('show');
        Route::resource('leagues', LeagueController::class)->except('show');
        Route::resource('teams', TeamController::class)->except('show');
        Route::resource('matches', MatchController::class)->except('show');

        Route::get('matches/{match}/odds', [OddController::class, 'index'])->name('odds.index');
        Route::post('matches/{match}/odds', [OddController::class, 'store'])->name('odds.store');

        Route::get('leagues/{league}/standings', [StandingController::class, 'index'])->name('standings.index');
        Route::post('leagues/{league}/standings', [StandingController::class, 'store'])->name('standings.store');
        Route::delete('leagues/{league}/standings/{standing}', [StandingController::class, 'destroy'])->name('standings.destroy');

        Route::resource('predictions', PredictionController::class)->except('show');
        Route::post('predictions/{prediction}/settle', [PredictionController::class, 'settle'])->name('predictions.settle');

        // People & billing — Administrator+ (enforced per-action via Gates/Policies inside each controller)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::resource('subscription-plans', SubscriptionPlanController::class)->except('show');
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

        // System — Administrator+, with Roles further hard-restricted to Super Admin inside RoleController
        Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::middleware('can:manage-system')->group(function () {
            Route::get('api-keys', [ApiKeyController::class, 'index'])->name('api-keys.index');
            Route::get('api-keys/create', [ApiKeyController::class, 'create'])->name('api-keys.create');
            Route::post('api-keys', [ApiKeyController::class, 'store'])->name('api-keys.store');
            Route::patch('api-keys/{apiKey}/toggle-active', [ApiKeyController::class, 'toggleActive'])->name('api-keys.toggle-active');
            Route::delete('api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
        });

        Route::middleware('role:super-administrator')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('roles/{role}', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });

        // Blog/CMS — Editor+
        Route::resource('categories', CategoryController::class)->except('show');
        Route::get('tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('tags', [TagController::class, 'store'])->name('tags.store');
        Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

        Route::resource('articles', ArticleController::class)->except('show');

        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::patch('comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam');
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        Route::resource('faqs', FaqController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('advertisements', AdvertisementController::class)->except('show');
        Route::resource('pages', PageController::class)->except('show');

        Route::get('page-sections', [PageSectionController::class, 'index'])->name('page-sections.index');
        Route::get('page-sections/{pageSection}/edit', [PageSectionController::class, 'edit'])->name('page-sections.edit');
        Route::put('page-sections/{pageSection}', [PageSectionController::class, 'update'])->name('page-sections.update');
        Route::patch('page-sections/{pageSection}/toggle-visible', [PageSectionController::class, 'toggleVisible'])->name('page-sections.toggle-visible');
        Route::patch('page-sections/{pageSection}/move-up', [PageSectionController::class, 'moveUp'])->name('page-sections.move-up');
        Route::patch('page-sections/{pageSection}/move-down', [PageSectionController::class, 'moveDown'])->name('page-sections.move-down');
        Route::resource('slides', SlideController::class)->except('show');

        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('menus/{menu}', [MenuController::class, 'edit'])->name('menus.edit');
        Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');

        Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
    });
