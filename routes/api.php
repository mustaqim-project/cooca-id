<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\LicenseValidationController;
use App\Http\Controllers\Api\V1\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - License Server & Webhooks
|--------------------------------------------------------------------------
|
| These routes are for the API endpoints, including license validation
| and payment webhooks. Protected by appropriate middleware.
|
*/

Route::prefix('api/v1')->name('api.v1.')->group(function () {
    // License Validation API (for ERP clients)
    Route::prefix('license')->group(function () {
        Route::post('/validate', [LicenseValidationController::class, 'validate'])->name('license.validate');
        Route::post('/heartbeat', [LicenseValidationController::class, 'heartbeat'])->name('license.heartbeat');
    });

    // Payment Webhooks
    Route::prefix('webhook')->group(function () {
        Route::post('/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhook.midtrans');
        Route::post('/midtrans/notification', [MidtransWebhookController::class, 'notification'])->name('webhook.midtrans.notification');
    });
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'service' => 'cooca.id-api',
        'version' => '1.0.0',
    ]);
})->name('health');