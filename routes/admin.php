<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AffiliatorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| These routes are for the admin dashboard, protected by auth:admin middleware.
| All admin routes use Inertia.js for Vue 3 integration.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Customers Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Affiliators Management
    Route::get('/affiliators', [AffiliatorController::class, 'index'])->name('affiliators.index');
    Route::get('/affiliators/{affiliator}', [AffiliatorController::class, 'show'])->name('affiliators.show');
    Route::put('/affiliators/{affiliator}', [AffiliatorController::class, 'update'])->name('affiliators.update');
    Route::delete('/affiliators/{affiliator}', [AffiliatorController::class, 'destroy'])->name('affiliators.destroy');

    // Licenses Management
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::post('/licenses/generate', [LicenseController::class, 'generate'])->name('licenses.generate');
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::post('/licenses/{license}/revoke', [LicenseController::class, 'revoke'])->name('licenses.revoke');
    Route::post('/licenses/{license}/activate', [LicenseController::class, 'activate'])->name('licenses.activate');

    // Subscriptions Management
    Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', 'SubscriptionController@show')->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/cancel', 'SubscriptionController@cancel')->name('subscriptions.cancel');

    // Transactions Management
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/refund', [TransactionController::class, 'refund'])->name('transactions.refund');

    // Vouchers Management
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}', [VoucherController::class, 'show'])->name('vouchers.show');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');

    // Settlements (Withdrawals) Management
    Route::get('/settlements', [SettlementController::class, 'index'])->name('settlements.index');
    Route::get('/settlements/{settlement}', [SettlementController::class, 'show'])->name('settlements.show');
    Route::post('/settlements/{settlement}/approve', [SettlementController::class, 'approve'])->name('settlements.approve');
    Route::post('/settlements/{settlement}/reject', [SettlementController::class, 'reject'])->name('settlements.reject');

    // CMS - Pages
    Route::get('/cms/pages', 'CmsController@index')->name('cms.pages.index');
    Route::get('/cms/pages/create', 'CmsController@create')->name('cms.pages.create');
    Route::post('/cms/pages', 'CmsController@store')->name('cms.pages.store');
    Route::get('/cms/pages/{page}/edit', 'CmsController@edit')->name('cms.pages.edit');
    Route::put('/cms/pages/{page}', 'CmsController@update')->name('cms.pages.update');
    Route::delete('/cms/pages/{page}', 'CmsController@destroy')->name('cms.pages.destroy');

    // CMS - Blog
    Route::get('/blog', 'BlogController@index')->name('blog.index');
    Route::get('/blog/create', 'BlogController@create')->name('blog.create');
    Route::post('/blog', 'BlogController@store')->name('blog.store');
    Route::get('/blog/{post}/edit', 'BlogController@edit')->name('blog.edit');
    Route::put('/blog/{post}', 'BlogController@update')->name('blog.update');
    Route::delete('/blog/{post}', 'BlogController@destroy')->name('blog.destroy');

    // Email Campaigns
    Route::get('/email-campaigns', 'EmailCampaignController@index')->name('email-campaigns.index');
    Route::get('/email-campaigns/create', 'EmailCampaignController@create')->name('email-campaigns.create');
    Route::post('/email-campaigns', 'EmailCampaignController@store')->name('email-campaigns.store');
    Route::get('/email-campaigns/{campaign}', 'EmailCampaignController@show')->name('email-campaigns.show');
    Route::post('/email-campaigns/{campaign}/send', 'EmailCampaignController@send')->name('email-campaigns.send');

    // Support Tickets
    Route::get('/tickets', 'TicketController@index')->name('tickets.index');
    Route::get('/tickets/{ticket}', 'TicketController@show')->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', 'TicketController@reply')->name('tickets.reply');
    Route::post('/tickets/{ticket}/resolve', 'TicketController@resolve')->name('tickets.resolve');
    Route::post('/tickets/{ticket}/close', 'TicketController@close')->name('tickets.close');

    // Reviews Moderation
    Route::get('/reviews', 'ReviewController@index')->name('reviews.index');
    Route::post('/reviews/{review}/approve', 'ReviewController@approve')->name('reviews.approve');
    Route::post('/reviews/{review}/reject', 'ReviewController@reject')->name('reviews.reject');
    Route::delete('/reviews/{review}', 'ReviewController@destroy')->name('reviews.destroy');

    // Settings
    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    Route::put('/settings', 'SettingsController@update')->name('settings.update');
});
