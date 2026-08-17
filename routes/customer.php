<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\LicenseController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\SubscriptionController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\ProfileController;

/*
|--------------------------------------------------------------------------
| Customer Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes are for the customer dashboard, protected by auth:customer middleware.
| All customer routes use Inertia.js for Vue 3 integration.
|
*/

Route::prefix('customer')->name('customer.')->middleware(['auth:customer', 'verified:customer.verification.notice', 'phone.verified', 'throttle:customer'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AI Gateway & API Key Management
    Route::get('/ai-usage', [\App\Http\Controllers\Customer\AiUsageController::class, 'index'])->name('ai-usage.index');
    Route::post('/ai-usage/keys', [\App\Http\Controllers\Customer\AiUsageController::class, 'createKey'])->name('ai-usage.keys.store');
    Route::delete('/ai-usage/keys/{key}', [\App\Http\Controllers\Customer\AiUsageController::class, 'revokeKey'])->name('ai-usage.keys.revoke');

    // Products Catalog
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    // Subscriptions - Using scoped route model binding for IDOR prevention
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/check-domain', [SubscriptionController::class, 'checkDomain'])->name('subscriptions.check-domain');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::match(['get', 'post'], '/subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::get('/subscriptions/{subscription}/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('/subscriptions/{subscription}/checkout', [SubscriptionController::class, 'processCheckout'])->name('subscriptions.checkout.process');
    Route::post('/subscriptions/{subscription}/apply-voucher', [SubscriptionController::class, 'applyVoucher'])->name('subscriptions.apply-voucher');

    // Payments - Using scoped route model binding for IDOR prevention
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    
    // Midtrans Redirect URLs
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/pending', [PaymentController::class, 'pending'])->name('payments.pending');
    Route::get('/payments/failed', [PaymentController::class, 'failed'])->name('payments.failed');
    Route::get('/payments/callback/{order_id}', [PaymentController::class, 'callback'])->name('payments.callback');
    
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

    // Invoices - Using scoped route model binding for IDOR prevention
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // Licenses - Using scoped route model binding for IDOR prevention
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::post('/licenses/{license}/activate', [LicenseController::class, 'activate'])->name('licenses.activate');
    Route::get('/licenses/{license}/credentials', [LicenseController::class, 'credentials'])->name('licenses.credentials');
    Route::post('/licenses/{license}/appeals', [\App\Http\Controllers\Customer\LicenseAppealController::class, 'store'])->name('licenses.appeals.store');

    // Reviews - Using policy-based authorization
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/my-reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Company Profile
    Route::get('/company-profile', [\App\Http\Controllers\Customer\CompanyProfileController::class, 'edit'])->name('company-profile.edit');
    Route::put('/company-profile', [\App\Http\Controllers\Customer\CompanyProfileController::class, 'update'])->name('company-profile.update');

    // Trials
    Route::get('/trials', [\App\Http\Controllers\Customer\TrialController::class, 'index'])->name('trials.index');
    Route::get('/trials/check-subdomain', [\App\Http\Controllers\Customer\TrialController::class, 'checkSubdomain'])->name('trials.check-subdomain');
    Route::get('/trials/create', [\App\Http\Controllers\Customer\TrialController::class, 'create'])->name('trials.create');
    Route::post('/trials', [\App\Http\Controllers\Customer\TrialController::class, 'store'])->name('trials.store');
    Route::get('/trials/{trial}', [\App\Http\Controllers\Customer\TrialController::class, 'show'])->name('trials.show');

    // Domains
    Route::get('/domains', [\App\Http\Controllers\Customer\DomainController::class, 'index'])->name('domains.index');
    Route::put('/domains/{tenant}', [\App\Http\Controllers\Customer\DomainController::class, 'update'])->name('domains.update');
    Route::post('/domains/{tenant}/verify', [\App\Http\Controllers\Customer\DomainController::class, 'verify'])->name('domains.verify');



    // Tickets
    Route::get('/tickets', [\App\Http\Controllers\Customer\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\Customer\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\Customer\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Customer\TicketController::class, 'show'])->name('tickets.show');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Projects
    Route::resource('projects', \App\Http\Controllers\Customer\ProjectController::class)->only(['index', 'show']);
    Route::get('projects/{project}/pay/{invoice}', [\App\Http\Controllers\Customer\ProjectController::class, 'checkout'])->name('projects.pay');

    // WhatsApp API Device Generator Management for Customer
    Route::resource('whatsapp-devices', \App\Http\Controllers\Customer\WhatsAppDeviceController::class);
    Route::get('/whatsapp-devices/{id}/status-ajax', [\App\Http\Controllers\Customer\WhatsAppDeviceController::class, 'statusAjax'])->name('whatsapp-devices.status-ajax');
    Route::post('/whatsapp-devices/{id}/test-send', [\App\Http\Controllers\Customer\WhatsAppDeviceController::class, 'testSend'])->name('whatsapp-devices.test-send');
});


