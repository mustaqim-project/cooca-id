<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Midtrans\WebhookController;
use App\Http\Controllers\Api\V1\MidtransWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Protected API routes (require authentication)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Add protected API routes here
});

/*
|--------------------------------------------------------------------------
| Midtrans Webhook Routes
|--------------------------------------------------------------------------
| Two endpoints available:
| 1. Legacy webhook handler (for backward compatibility)
| 2. New V1 webhook handler with enhanced security and idempotency
|
| For production, use the V1 endpoint: /api/v1/midtrans/webhook
| Configure this URL in your Midtrans dashboard settings.
*/

// Legacy webhook route (kept for backward compatibility during migration)
Route::middleware(['throttle:midtrans-webhook'])->group(function () {
    Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])
        ->name('api.midtrans.webhook.legacy');
});

// NEW: Production-ready V1 webhook handler with idempotency and enhanced security
Route::middleware(['throttle:midtrans-webhook'])->group(function () {
    Route::post('/v1/midtrans/webhook', [MidtransWebhookController::class, 'midtrans'])
        ->name('api.midtrans.webhook.v1');
});

// License validation endpoint for ERP systems
Route::post('/v1/license/validate', [\App\Http\Controllers\Api\V1\LicenseValidationController::class, 'validate'])
    ->name('api.license.validate');
