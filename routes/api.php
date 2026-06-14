<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Midtrans\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Add protected API routes here
});

// Midtrans webhook route with rate limiting
Route::middleware(['throttle:midtrans-webhook'])->group(function () {
    Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])
        ->name('api.midtrans.webhook');
});
