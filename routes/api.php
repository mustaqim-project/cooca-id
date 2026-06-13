<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Midtrans\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API routes
Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])
    ->name('api.midtrans.webhook');

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Add protected API routes here
});
