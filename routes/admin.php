<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\FinanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AffiliatorController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
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
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ErrorLogController;
use App\Http\Controllers\Admin\TrialManagementController;
use App\Http\Controllers\Admin\ProfileController;

Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'throttle:admin'])->group(function () {
    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AI Gateway Dashboard
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AiDashboardController::class, 'index'])->name('dashboard');
        Route::post('/cycles/{cycle}/bonus', [\App\Http\Controllers\Admin\AiDashboardController::class, 'grantBonus'])->name('cycles.bonus');
    });

    // Live Chat Support Management
    Route::prefix('live-chats')->name('live-chats.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminLiveChatController::class, 'index'])->name('index');
        Route::get('/sessions-data', [\App\Http\Controllers\Admin\AdminLiveChatController::class, 'getSessions'])->name('sessions-data');
        Route::get('/{id}/messages', [\App\Http\Controllers\Admin\AdminLiveChatController::class, 'getMessages'])->name('messages');
        Route::post('/{id}/reply', [\App\Http\Controllers\Admin\AdminLiveChatController::class, 'reply'])->name('reply');
        Route::post('/{id}/end', [\App\Http\Controllers\Admin\AdminLiveChatController::class, 'endChat'])->name('end');
    });


    // ERP Requests Management
    Route::get('/erp-requests', [ErpRequestController::class, 'index'])->name('erp-requests.index');
    Route::get('/erp-requests/{erpRequest}', [ErpRequestController::class, 'show'])->name('erp-requests.show');
    Route::post('/erp-requests/{erpRequest}/approve', [ErpRequestController::class, 'approve'])->name('erp-requests.approve');
    Route::post('/erp-requests/{erpRequest}/reject', [ErpRequestController::class, 'reject'])->name('erp-requests.reject');
    Route::post('/erp-requests/{erpRequest}/mark-waiting-setup', [ErpRequestController::class, 'markWaitingSetup'])->name('erp-requests.mark-waiting-setup');
    Route::post('/erp-requests/{erpRequest}/mark-in-setup', [ErpRequestController::class, 'markInSetup'])->name('erp-requests.mark-in-setup');
    Route::post('/erp-requests/{erpRequest}/mark-domain-setup', [ErpRequestController::class, 'markDomainSetup'])->name('erp-requests.mark-domain-setup');
    Route::post('/erp-requests/{erpRequest}/mark-testing', [ErpRequestController::class, 'markTesting'])->name('erp-requests.mark-testing');
    Route::post('/erp-requests/{erpRequest}/confirm-ready', [ErpRequestController::class, 'confirmReady'])->name('erp-requests.confirm-ready');

    // Products Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Product Pricing Plans Management
    Route::get('/products/{product}/plans', [SubscriptionPlanController::class, 'index'])->name('products.plans.index');
    Route::post('/products/{product}/plans', [SubscriptionPlanController::class, 'store'])->name('products.plans.store');
    Route::put('/products/{product}/plans/{plan}', [SubscriptionPlanController::class, 'update'])->name('products.plans.update');
    Route::delete('/products/{product}/plans/{plan}', [SubscriptionPlanController::class, 'destroy'])->name('products.plans.destroy');
    Route::post('/products/{product}/plans/{plan}/toggle', [SubscriptionPlanController::class, 'toggle'])->name('products.plans.toggle');


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
    Route::post('/affiliators/{affiliator}/suspend', [AffiliatorController::class, 'suspend'])->name('affiliators.suspend');
    Route::post('/affiliators/{affiliator}/reactivate', [AffiliatorController::class, 'reactivate'])->name('affiliators.reactivate');

    // Licenses Management
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::post('/licenses/generate', [LicenseController::class, 'generate'])->name('licenses.generate');
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::post('/licenses/{license}/revoke', [LicenseController::class, 'revoke'])->name('licenses.revoke');
    Route::post('/licenses/{license}/activate', [LicenseController::class, 'activate'])->name('licenses.activate');
    Route::post('/licenses/{license}/appeals/{appeal}/approve', [LicenseController::class, 'approveAppeal'])->name('licenses.appeals.approve');
    Route::post('/licenses/{license}/appeals/{appeal}/reject', [LicenseController::class, 'rejectAppeal'])->name('licenses.appeals.reject');

    // Finance & Reporting
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');

    // Payment Methods Reporting
    Route::get('/reports/payments', [\App\Http\Controllers\Admin\PaymentReportController::class, 'index'])->name('reports.payments.index');
    Route::get('/reports/payments/export', [\App\Http\Controllers\Admin\PaymentReportController::class, 'export'])->name('reports.payments.export');

    // Full Accounting (ERP Port)
    Route::get('/accounting/coa', [App\Http\Controllers\Admin\AccountingController::class, 'coaIndex'])->name('accounting.coa.index');
    Route::post('/accounting/coa', [App\Http\Controllers\Admin\AccountingController::class, 'coaStore'])->name('accounting.coa.store');
    Route::get('/accounting/journal', [App\Http\Controllers\Admin\AccountingController::class, 'journalIndex'])->name('accounting.journal.index');
    Route::get('/accounting/journal/create', [App\Http\Controllers\Admin\AccountingController::class, 'journalCreate'])->name('accounting.journal.create');
    Route::post('/accounting/journal', [App\Http\Controllers\Admin\AccountingController::class, 'journalStore'])->name('accounting.journal.store');
    Route::get('/accounting/ledger', [App\Http\Controllers\Admin\AccountingController::class, 'reportLedger'])->name('accounting.reports.ledger');
    Route::get('/accounting/profit-loss', [App\Http\Controllers\Admin\AccountingController::class, 'reportProfitLoss'])->name('accounting.reports.profit-loss');

    // Subscriptions Management
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('subscriptions/validate-domain', [SubscriptionController::class, 'validateDomain'])->name('subscriptions.validate-domain');

    // Transactions Management
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/verify', [TransactionController::class, 'verify'])->name('transactions.verify');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
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
    Route::post('/settlements/{settlement}/mark-as-paid', [SettlementController::class, 'markAsPaid'])->name('settlements.markAsPaid');

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

    // CMS - Blog Categories & Posts
    Route::resource('blog-categories', BlogCategoryController::class);
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
    Route::get('/email-campaigns/{campaign}/edit', [EmailCampaignController::class, 'edit'])->name('email-campaigns.edit');
    Route::put('/email-campaigns/{campaign}', [EmailCampaignController::class, 'update'])->name('email-campaigns.update');
    Route::delete('/email-campaigns/{campaign}', [EmailCampaignController::class, 'destroy'])->name('email-campaigns.destroy');
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

    // System & Security Logs
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
    Route::get('error-logs', [ErrorLogController::class, 'index'])->name('error-logs.index');
    Route::delete('error-logs/clear', [ErrorLogController::class, 'clear'])->name('error-logs.clear');
    
    // Blocked IPs
    Route::resource('blocked-ips', \App\Http\Controllers\Admin\BlockedIpController::class)->only(['index', 'store', 'destroy']);

    // Settings & Company Bank Accounts CMS
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('bank-accounts', \App\Http\Controllers\Admin\CompanyBankAccountController::class);
    Route::post('bank-accounts/{bank_account}/toggle-active', [\App\Http\Controllers\Admin\CompanyBankAccountController::class, 'toggleActive'])->name('bank-accounts.toggle-active');
    Route::post('bank-accounts/{bank_account}/set-primary', [\App\Http\Controllers\Admin\CompanyBankAccountController::class, 'setPrimary'])->name('bank-accounts.set-primary');
    Route::post('bank-accounts/reorder', [\App\Http\Controllers\Admin\CompanyBankAccountController::class, 'reorder'])->name('bank-accounts.reorder');

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

    // Trial Management
    Route::get('/trials', [TrialManagementController::class, 'index'])->name('trials.index');
    Route::get('/trials/{trial}', [TrialManagementController::class, 'show'])->name('trials.show');
    Route::post('/trials/{trial}/approve', [TrialManagementController::class, 'approve'])->name('trials.approve');
    Route::post('/trials/{trial}/reject', [TrialManagementController::class, 'reject'])->name('trials.reject');
    Route::post('/trials/{trial}/mark-domain-setup', [TrialManagementController::class, 'markDomainSetup'])->name('trials.mark-domain-setup');
    Route::post('/trials/{trial}/mark-testing', [TrialManagementController::class, 'markTesting'])->name('trials.mark-testing');
    Route::post('/trials/{trial}/start-trial', [TrialManagementController::class, 'startTrial'])->name('trials.start-trial');
    Route::get('/trials/stats', [TrialManagementController::class, 'stats'])->name('trials.stats');

    // API Integrations Management
    Route::get('/api-integrations', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'index'])->name('api-integrations.index');
    Route::get('/api-integrations/{provider}/edit', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'edit'])->name('api-integrations.edit');
    Route::put('/api-integrations/{provider}', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'update'])->name('api-integrations.update');
    Route::post('/api-integrations/{provider}/toggle', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'toggle'])->name('api-integrations.toggle');
    Route::post('/api-integrations/{provider}/test', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'test'])->name('api-integrations.test');

    // Deal & Project Management
    Route::resource('pipelines', \App\Http\Controllers\Admin\PipelineController::class);
    Route::resource('stages', \App\Http\Controllers\Admin\StageController::class);
    Route::resource('deals', \App\Http\Controllers\Admin\DealController::class);
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    Route::post('projects/{project}/billing', [\App\Http\Controllers\Admin\ProjectController::class, 'createBilling'])->name('projects.billing');
    Route::resource('project-tasks', \App\Http\Controllers\Admin\ProjectTaskController::class);

    // WhatsApp API Device Generator Management
    Route::resource('whatsapp-devices', \App\Http\Controllers\Admin\WhatsAppDeviceController::class);
    Route::get('/whatsapp-devices/{id}/status-ajax', [\App\Http\Controllers\Admin\WhatsAppDeviceController::class, 'statusAjax'])->name('whatsapp-devices.status-ajax');
    Route::post('/whatsapp-devices/{id}/test-send', [\App\Http\Controllers\Admin\WhatsAppDeviceController::class, 'testSend'])->name('whatsapp-devices.test-send');

    // Live Chat Quick Templates Management
    Route::resource('live-chat-templates', \App\Http\Controllers\Admin\LiveChatTemplateController::class);
});



