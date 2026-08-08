<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Midtrans\WebhookController;
use App\Http\Controllers\Api\V1\MidtransWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API routes
Route::prefix('v1')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\V1\CatalogApiController::class, 'products']);
    Route::get('/products/{product}', [\App\Http\Controllers\Api\V1\CatalogApiController::class, 'show']);
    Route::get('/products/{product}/plans', [\App\Http\Controllers\Api\V1\CatalogApiController::class, 'plans']);
});

// Protected API routes (require authentication)
Route::prefix('v1')->middleware(['throttle:api'])->group(function () {

    // Customer API
    Route::middleware(['auth:sanctum', 'type.customer'])->prefix('customer')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'profile']);
        Route::put('/profile', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'updateProfile']);
        Route::get('/company', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'company']);
        Route::put('/company', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'updateCompany']);
        Route::get('/trials', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'trials']);
        Route::post('/trials', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'requestTrial']);
        Route::get('/subscriptions', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'subscriptions']);
        Route::get('/invoices', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'invoices']);
        Route::get('/licenses', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'licenses']);
        Route::get('/tickets', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'tickets']);
        Route::post('/tickets', [\App\Http\Controllers\Api\V1\CustomerApiController::class, 'createTicket']);
    });

    // Affiliator API
    Route::middleware(['auth:sanctum', 'type.affiliator'])->prefix('affiliator')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'dashboard']);
        Route::get('/referrals', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'referrals']);
        Route::get('/commissions', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'commissions']);
        Route::get('/withdrawals', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'withdrawals']);
        Route::post('/withdrawals', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'requestWithdrawal']);
        Route::get('/downlines', [\App\Http\Controllers\Api\V1\AffiliatorApiController::class, 'downlines']);
    });
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

// Public Fonnte-style External WhatsApp API Endpoint (X-WA-API-KEY Auth)
Route::post('/v1/wa/send', [\App\Http\Controllers\Api\WhatsAppPublicApiController::class, 'send'])
    ->name('api.wa.send');

// WhatsApp Gateway Webhook for Incoming & Outgoing WA Mobile Messages
Route::post('/v1/wa/webhook', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'handle'])
    ->name('api.wa.webhook');

// WA Worker Endpoints

Route::prefix('wa/worker')->group(function () {
    Route::get('/queue', [\App\Http\Controllers\Api\WhatsAppWorkerController::class, 'getQueue']);
    Route::post('/update', [\App\Http\Controllers\Api\WhatsAppWorkerController::class, 'updateQueue']);
});

