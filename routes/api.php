<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Midtrans\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rate limiter configuration
RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by($request->ip());
});

RateLimiter::for('midtrans-webhook', function ($request) {
    // Allow higher limit for Midtrans webhook but still protect from abuse
    return Limit::perMinute(120)->by($request->ip());
});

// Public API routes with rate limiting
Route::middleware(['throttle:midtrans-webhook'])->group(function () {
    Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])
        ->name('api.midtrans.webhook');
});

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Add protected API routes here
});
