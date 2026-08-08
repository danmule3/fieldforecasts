<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::post('/articles/{article:slug}/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('comments.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Kept last: a slug-only catch-all for static Pages (About, Privacy
// Policy, Terms, etc). Anything above this line takes priority.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');
