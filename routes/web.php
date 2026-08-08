<?php

declare(strict_types=1);

use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BlogWebController;
use App\Http\Controllers\Web\CustomerOtpController;
use App\Http\Controllers\Web\DirectPaymentController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\LiveChatController;
use App\Http\Controllers\Web\SitemapController;
use App\Http\Controllers\Web\WhatsAppChatbotController;
use App\Http\Controllers\Customer\ContractController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Landing Page & Public Pages
|--------------------------------------------------------------------------
|
| These routes are for the public-facing landing pages, blog, and auth.
| They use the 'web' middleware group for session state, CSRF protection, etc.
|
*/

// Landing Pages
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/affiliate', [LandingController::class, 'affiliate'])->name('affiliate');
Route::get('/affiliate-terms', [LandingController::class, 'affiliateTerms'])->name('affiliate.terms');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');
Route::get('/lang/{locale}', [LandingController::class, 'switchLang'])->name('lang.switch');
Route::post('/newsletter/subscribe', [LandingController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/wa-chat-send', [WhatsAppChatbotController::class, 'send'])->name('wa.chat.send');
Route::get('/captcha/refresh', function () {
    return response()->json(['question' => \App\Helpers\CaptchaHelper::generate()]);
})->name('captcha.refresh');

// Live Chat Widget Routes
Route::prefix('live-chat')->name('live-chat.')->group(function () {
    Route::get('/options', [LiveChatController::class, 'getOptions'])->name('options');
    Route::post('/start', [LiveChatController::class, 'start'])->name('start');
    Route::get('/messages', [LiveChatController::class, 'getMessages'])->name('messages');
    Route::post('/send', [LiveChatController::class, 'sendMessage'])->name('send');
    Route::post('/end', [LiveChatController::class, 'end'])->name('end');
});

// Product Catalog
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [LandingController::class, 'products'])->name('index');
    Route::get('/{slug}', [LandingController::class, 'productShow'])->name('show');
});

// Blog
Route::get('/blog', [BlogWebController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogWebController::class, 'show'])->name('blog.show');

// Direct Payment (No Login Required)
Route::get('/pay/direct/{subscription}', [DirectPaymentController::class, 'checkout'])
    ->name('payment.direct')
    ->middleware('signed');

// Customer Auth Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [AuthWebController::class, 'showCustomerLogin'])->name('login');
        Route::post('login', [AuthWebController::class, 'customerLogin'])->middleware('throttle:customer-login')->name('login.submit');
        Route::get('register', [AuthWebController::class, 'showCustomerRegister'])->name('register');
        Route::post('register', [AuthWebController::class, 'customerRegister'])->middleware('throttle:customer-register')->name('register.submit');

        // Google OAuth
        Route::get('auth/google', [AuthWebController::class, 'redirectToGoogleCustomer'])->name('auth.google');
        Route::get('auth/google/callback', [AuthWebController::class, 'handleGoogleCallbackCustomer']);

        // Password Reset
        Route::get('forgot-password', [AuthWebController::class, 'showCustomerForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthWebController::class, 'sendCustomerResetLink'])->middleware('throttle:customer-login');
        Route::get('reset-password/{token}', [AuthWebController::class, 'showCustomerReset'])->name('password.reset');
        Route::post('reset-password', [AuthWebController::class, 'resetCustomerPassword'])->middleware('throttle:customer-login');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [AuthWebController::class, 'customerLogout'])->name('logout');

        // Email Verification
        Route::get('email/verify', [AuthWebController::class, 'showCustomerVerificationNotice'])->name('verification.notice');
        Route::get('email/verify/{id}/{hash}', [AuthWebController::class, 'verifyCustomerEmail'])->middleware('signed')->name('verification.verify');
        Route::post('email/verification-notification', [AuthWebController::class, 'resendCustomerVerificationEmail'])->middleware('throttle:6,1')->name('verification.send');

        // Phone Verification (OTP)
        Route::get('phone/verify', [CustomerOtpController::class, 'showNotice'])->name('otp.notice');
        Route::post('phone/verify', [CustomerOtpController::class, 'verify'])->name('otp.verify');
        Route::post('phone/resend-otp', [CustomerOtpController::class, 'resend'])->middleware('throttle:6,1')->name('otp.resend');
        Route::post('phone/update', [CustomerOtpController::class, 'updatePhone'])->name('otp.update_phone');

        // Contract Routes
        Route::get('contracts/{licenseId}', [ContractController::class, 'show'])->name('contracts.show');
        Route::post('contracts/{licenseId}/sign', [ContractController::class, 'sign'])->name('contracts.sign');
        Route::get('contracts/{licenseId}/download', [ContractController::class, 'download'])->name('contracts.download');
    });
});

// Affiliator Auth Routes
Route::prefix('affiliator')->name('affiliator.')->group(function () {
    Route::middleware('guest:affiliator')->group(function () {
        Route::get('login', [AuthWebController::class, 'showAffiliatorLogin'])->name('login');
        Route::post('login', [AuthWebController::class, 'affiliatorLogin'])->middleware('throttle:affiliator-login')->name('login.submit');
        Route::get('register', [AuthWebController::class, 'showAffiliatorRegister'])->name('register');
        Route::post('register', [AuthWebController::class, 'affiliatorRegister'])->middleware('throttle:register')->name('register.submit');

        // Google OAuth
        Route::get('auth/google', [AuthWebController::class, 'redirectToGoogleAffiliator'])->name('auth.google');
        Route::get('auth/google/callback', [AuthWebController::class, 'handleGoogleCallbackAffiliator'])->name('auth.google.callback');

        // Password Reset for Affiliator
        Route::get('forgot-password', [AuthWebController::class, 'showAffiliatorForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthWebController::class, 'sendAffiliatorResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [AuthWebController::class, 'showAffiliatorReset'])->name('password.reset');
        Route::post('reset-password', [AuthWebController::class, 'resetAffiliatorPassword'])->name('password.update');
    });

    Route::middleware('auth:affiliator')->group(function () {
        Route::post('logout', [AuthWebController::class, 'affiliatorLogout'])->name('logout');
    });
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthWebController::class, 'showAdminLogin'])->name('login');
        Route::post('login', [AuthWebController::class, 'adminLogin'])->middleware('throttle:admin-login')->name('login.submit');

        // Password Reset for Admin
        Route::get('forgot-password', [AuthWebController::class, 'showAdminForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthWebController::class, 'sendAdminResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [AuthWebController::class, 'showAdminReset'])->name('password.reset');
        Route::post('reset-password', [AuthWebController::class, 'resetAdminPassword'])->name('password.update');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthWebController::class, 'adminLogout'])->name('logout');
    });
});

Route::get('/login', [AuthWebController::class, 'showCustomerLogin'])->name('login');

Route::get('/clear-app-cache', [\App\Http\Controllers\Web\SystemController::class, 'clearAppCache']);

Route::get('/debug-mail-config', function () {
    $latestFailed = \Illuminate\Support\Facades\DB::table('failed_jobs')->latest('failed_at')->first();
    return response()->json([
        'default_mailer' => config('mail.default'),
        'mail_from' => config('mail.from'),
        'queue_connection' => config('queue.default'),
        'jobs_count' => \Illuminate\Support\Facades\DB::table('jobs')->count(),
        'failed_jobs_count' => \Illuminate\Support\Facades\DB::table('failed_jobs')->count(),
        'latest_failed_job' => $latestFailed ? [
            'id' => $latestFailed->id,
            'connection' => $latestFailed->connection,
            'queue' => $latestFailed->queue,
            'failed_at' => $latestFailed->failed_at,
            'exception' => substr($latestFailed->exception, 0, 1000), // First 1000 chars of exception
        ] : null,
        'mailers' => collect(config('mail.mailers'))->map(function ($mailer) {
            if (isset($mailer['password'])) {
                $mailer['password'] = '******'; // Hide password for security
            }
            return $mailer;
        }),
    ]);
});
