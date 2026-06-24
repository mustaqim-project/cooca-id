<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\NewsletterController;
use App\Http\Controllers\Web\ProductController;
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
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/affiliate', [LandingController::class, 'affiliate'])->name('affiliate');
Route::get('/solutions', [LandingController::class, 'solution'])->name('solutions');
Route::get('/features', [LandingController::class, 'features'])->name('features');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/docs', [LandingController::class, 'docs'])->name('docs');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');
Route::post('/newsletter/subscribe', [LandingController::class, 'subscribe'])->name('newsletter.subscribe');
// Product Catalog
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [LandingController::class, 'products'])->name('index');
    // Show product detail page
    Route::get('/{slug}', [LandingController::class, 'productShow'])->name('show');
});

// Blog
Route::get('/blog', [LandingController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [LandingController::class, 'blogShow'])->name('blog.show');
// Customer Auth Routes
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

RateLimiter::for('customer-login', function ($request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('customer-register', function ($request) {
    return Limit::perMinute(10)->by($request->ip());
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [LandingController::class, 'showCustomerLogin'])->name('login');
        Route::post('login', [LandingController::class, 'customerLogin'])->middleware('throttle:customer-login')->name('login.submit');
        Route::get('register', [LandingController::class, 'showCustomerRegister'])->name('register');
        Route::post('register', [LandingController::class, 'customerRegister'])->middleware('throttle:customer-register')->name('register.submit');

        // Google OAuth
        Route::get('auth/google', [LandingController::class, 'redirectToGoogleCustomer'])->name('auth.google');
        Route::get('auth/google/callback', [LandingController::class, 'handleGoogleCallbackCustomer']);

        // Password Reset
        Route::get('forgot-password', [LandingController::class, 'showCustomerForgotPassword'])->name('password.request');
        Route::post('forgot-password', [LandingController::class, 'sendCustomerResetLink'])->middleware('throttle:customer-login');
        Route::get('reset-password/{token}', [LandingController::class, 'showCustomerReset'])->name('password.reset');
        Route::post('reset-password', [LandingController::class, 'resetCustomerPassword'])->middleware('throttle:customer-login');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [LandingController::class, 'customerLogout'])->name('logout');
    });
});

// Affiliator Auth Routes
Route::prefix('affiliator')->name('affiliator.')->group(function () {
    Route::middleware('guest:affiliator')->group(function () {
        Route::get('login', [LandingController::class, 'showAffiliatorLogin'])->name('login');
        Route::post('login', [LandingController::class, 'affiliatorLogin'])->name('login.submit');
        Route::get('register', [LandingController::class, 'showAffiliatorRegister'])->name('register');
        Route::post('register', [LandingController::class, 'affiliatorRegister'])->name('register.submit');

        // Password Reset for Affiliator
        Route::get('forgot-password', [LandingController::class, 'showAffiliatorForgotPassword'])->name('password.request');
        Route::post('forgot-password', [LandingController::class, 'sendAffiliatorResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [LandingController::class, 'showAffiliatorReset'])->name('password.reset');
        Route::post('reset-password', [LandingController::class, 'resetAffiliatorPassword'])->name('password.update');
    });

    Route::middleware('auth:affiliator')->group(function () {
        Route::post('logout', [LandingController::class, 'affiliatorLogout'])->name('logout');
    });
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LandingController::class, 'showAdminLogin'])->name('login');
        Route::post('login', [LandingController::class, 'adminLogin'])->name('login.submit');

        // Password Reset for Admin
        Route::get('forgot-password', [LandingController::class, 'showAdminForgotPassword'])->name('password.request');
        Route::post('forgot-password', [LandingController::class, 'sendAdminResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [LandingController::class, 'showAdminReset'])->name('password.reset');
        Route::post('reset-password', [LandingController::class, 'resetAdminPassword'])->name('password.update');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [LandingController::class, 'adminLogout'])->name('logout');
    });
});

Route::get('/login', [LandingController::class, 'showCustomerLogin'])->name('login');

// Legal Pages Routes (CMS Managed)
