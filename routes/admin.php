<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Admin\AffiliatorController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailCampaignController;
use App\Http\Controllers\Admin\LandingCmsController;
use App\Http\Controllers\Admin\ErpRequestController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VoucherController;

// Rate limiter for admin routes
RateLimiter::for('admin', function ($request) {
    return Limit::perMinute(100)->by($request->user()?->id ?? $request->ip());
});

RateLimiter::for('admin-login', function ($request) {
    return Limit::perMinute(5)->by($request->ip());
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'throttle:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ERP Requests Management
    Route::get('/erp-requests', [ErpRequestController::class, 'index'])->name('erp-requests.index');
    Route::get('/erp-requests/{request}', [ErpRequestController::class, 'show'])->name('erp-requests.show');
    Route::post('/erp-requests/{request}/approve', [ErpRequestController::class, 'approve'])->name('erp-requests.approve');
    Route::post('/erp-requests/{request}/reject', [ErpRequestController::class, 'reject'])->name('erp-requests.reject');
    Route::post('/erp-requests/{request}/mark-waiting-setup', [ErpRequestController::class, 'markWaitingSetup'])->name('erp-requests.mark-waiting-setup');
    Route::post('/erp-requests/{request}/mark-in-setup', [ErpRequestController::class, 'markInSetup'])->name('erp-requests.mark-in-setup');
    Route::post('/erp-requests/{request}/mark-domain-setup', [ErpRequestController::class, 'markDomainSetup'])->name('erp-requests.mark-domain-setup');
    Route::post('/erp-requests/{request}/mark-testing', [ErpRequestController::class, 'markTesting'])->name('erp-requests.mark-testing');
    Route::post('/erp-requests/{request}/confirm-ready', [ErpRequestController::class, 'confirmReady'])->name('erp-requests.confirm-ready');

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
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Affiliators Management
    Route::get('/affiliators', [AffiliatorController::class, 'index'])->name('affiliators.index');
    Route::get('/affiliators/create', [AffiliatorController::class, 'create'])->name('affiliators.create');
    Route::post('/affiliators', [AffiliatorController::class, 'store'])->name('affiliators.store');
    Route::get('/affiliators/{affiliator}', [AffiliatorController::class, 'show'])->name('affiliators.show');
    Route::get('/affiliators/{affiliator}/edit', [AffiliatorController::class, 'edit'])->name('affiliators.edit');
    Route::put('/affiliators/{affiliator}', [AffiliatorController::class, 'update'])->name('affiliators.update');
    Route::delete('/affiliators/{affiliator}', [AffiliatorController::class, 'destroy'])->name('affiliators.destroy');

    // Licenses Management
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::post('/licenses/generate', [LicenseController::class, 'generate'])->name('licenses.generate');
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::post('/licenses/{license}/revoke', [LicenseController::class, 'revoke'])->name('licenses.revoke');
    Route::post('/licenses/{license}/activate', [LicenseController::class, 'activate'])->name('licenses.activate');

    // Subscriptions Management
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

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
    Route::post('/vouchers/{voucher}/activate', [VoucherController::class, 'activate'])->name('vouchers.activate');
    Route::post('/vouchers/{voucher}/deactivate', [VoucherController::class, 'deactivate'])->name('vouchers.deactivate');

    // Settlements (Withdrawals) Management
    Route::get('/settlements', [SettlementController::class, 'index'])->name('settlements.index');
    Route::get('/settlements/{settlement}', [SettlementController::class, 'show'])->name('settlements.show');
    Route::post('/settlements/{settlement}/approve', [SettlementController::class, 'approve'])->name('settlements.approve');
    Route::post('/settlements/{settlement}/reject', [SettlementController::class, 'reject'])->name('settlements.reject');

    // CMS - Landing
    Route::get('/cms/landing', [LandingCmsController::class, 'index'])->name('cms.landing.index');
    Route::post('/cms/landing', [LandingCmsController::class, 'update'])->name('cms.landing.update');

    // CMS - Pages
    Route::get('/cms/pages', [CmsController::class, 'index'])->name('cms.pages.index');
    Route::get('/cms/pages/create', [CmsController::class, 'create'])->name('cms.pages.create');
    Route::post('/cms/pages', [CmsController::class, 'store'])->name('cms.pages.store');
    Route::get('/cms/pages/{page}/edit', [CmsController::class, 'edit'])->name('cms.pages.edit');
    Route::put('/cms/pages/{page}', [CmsController::class, 'update'])->name('cms.pages.update');
    Route::delete('/cms/pages/{page}', [CmsController::class, 'destroy'])->name('cms.pages.destroy');

    // CMS - Blog
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');

    // Email Campaigns
    Route::get('/email-campaigns', [EmailCampaignController::class, 'index'])->name('email-campaigns.index');
    Route::get('/email-campaigns/create', [EmailCampaignController::class, 'create'])->name('email-campaigns.create');
    Route::post('/email-campaigns', [EmailCampaignController::class, 'store'])->name('email-campaigns.store');
    Route::get('/email-campaigns/{campaign}', [EmailCampaignController::class, 'show'])->name('email-campaigns.show');
    Route::post('/email-campaigns/{campaign}/send', [EmailCampaignController::class, 'send'])->name('email-campaigns.send');

    // Support Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');

    // Reviews Moderation
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Product Categories Management
    Route::get('/product-categories', [App\Http\Controllers\Admin\ProductCategoryController::class, 'index'])->name('product-categories.index');
    Route::get('/product-categories/create', [App\Http\Controllers\Admin\ProductCategoryController::class, 'create'])->name('product-categories.create');
    Route::post('/product-categories', [App\Http\Controllers\Admin\ProductCategoryController::class, 'store'])->name('product-categories.store');
    Route::get('/product-categories/{category}', [App\Http\Controllers\Admin\ProductCategoryController::class, 'show'])->name('product-categories.show');
    Route::get('/product-categories/{category}/edit', [App\Http\Controllers\Admin\ProductCategoryController::class, 'edit'])->name('product-categories.edit');
    Route::put('/product-categories/{category}', [App\Http\Controllers\Admin\ProductCategoryController::class, 'update'])->name('product-categories.update');
    Route::delete('/product-categories/{category}', [App\Http\Controllers\Admin\ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');
    Route::post('/product-categories/reorder', [App\Http\Controllers\Admin\ProductCategoryController::class, 'reorder'])->name('product-categories.reorder');
    Route::post('/product-categories/{category}/toggle-active', [App\Http\Controllers\Admin\ProductCategoryController::class, 'toggleActive'])->name('product-categories.toggle-active');

    // Email Templates Management
    Route::get('/email-templates', [App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/create', [App\Http\Controllers\Admin\EmailTemplateController::class, 'create'])->name('email-templates.create');
    Route::post('/email-templates', [App\Http\Controllers\Admin\EmailTemplateController::class, 'store'])->name('email-templates.store');
    Route::get('/email-templates/{template}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'show'])->name('email-templates.show');
    Route::get('/email-templates/{template}/edit', [App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::put('/email-templates/{template}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::delete('/email-templates/{template}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');
    Route::post('/email-templates/{template}/toggle-active', [App\Http\Controllers\Admin\EmailTemplateController::class, 'toggleActive'])->name('email-templates.toggle-active');
    Route::get('/email-templates/{template}/preview', [App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');

    // FAQs Management
    Route::get('/faqs', [App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create', [App\Http\Controllers\Admin\FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit', [App\Http\Controllers\Admin\FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}', [App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::post('/faqs/reorder', [App\Http\Controllers\Admin\FaqController::class, 'reorder'])->name('faqs.reorder');

    // Testimonials Management
    Route::get('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [App\Http\Controllers\Admin\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [App\Http\Controllers\Admin\TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::post('/testimonials/reorder', [App\Http\Controllers\Admin\TestimonialController::class, 'reorder'])->name('testimonials.reorder');
    Route::post('/testimonials/{testimonial}/toggle-featured', [App\Http\Controllers\Admin\TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
});
