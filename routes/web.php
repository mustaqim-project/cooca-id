<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\LandingController;
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
Route::get('/features', [LandingController::class, 'features'])->name('features');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/docs', [LandingController::class, 'docs'])->name('docs');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');

// Product Catalog
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [LandingController::class, 'products'])->name('index');
    Route::get('/erp-restoran', [LandingController::class, 'productRestoran'])->name('restoran');
    Route::get('/erp-klinik', [LandingController::class, 'productKlinik'])->name('klinik');
    Route::get('/erp-bengkel', [LandingController::class, 'productBengkel'])->name('bengkel');
    Route::get('/erp-legal', [LandingController::class, 'productLegal'])->name('legal');
});

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Customer Auth Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [AuthController::class, 'showCustomerLogin'])->name('login');
        Route::post('login', [AuthController::class, 'customerLogin']);
        Route::get('register', [AuthController::class, 'showCustomerRegister'])->name('register');
        Route::post('register', [AuthController::class, 'customerRegister']);
        
        // Google OAuth
        Route::get('auth/google', [AuthController::class, 'redirectToGoogleCustomer'])->name('auth.google');
        Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallbackCustomer']);
        
        // Password Reset
        Route::get('forgot-password', [PasswordResetController::class, 'showCustomerForgotPassword'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendCustomerResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showCustomerReset'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'resetCustomerPassword'])->name('password.update');
    });
    
    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [AuthController::class, 'customerLogout'])->name('logout');
    });
});

// Affiliator Auth Routes
Route::prefix('affiliator')->name('affiliator.')->group(function () {
    Route::middleware('guest:affiliator')->group(function () {
        Route::get('login', [AuthController::class, 'showAffiliatorLogin'])->name('login');
        Route::post('login', [AuthController::class, 'affiliatorLogin']);
        Route::get('register', [AuthController::class, 'showAffiliatorRegister'])->name('register');
        Route::post('register', [AuthController::class, 'affiliatorRegister']);
        
        // Password Reset for Affiliator
        Route::get('forgot-password', [PasswordResetController::class, 'showAffiliatorForgotPassword'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendAffiliatorResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showAffiliatorReset'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'resetAffiliatorPassword'])->name('password.update');
    });
    
    Route::middleware('auth:affiliator')->group(function () {
        Route::post('logout', [AuthController::class, 'affiliatorLogout'])->name('logout');
    });
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showAdminLogin'])->name('login');
        Route::post('login', [AuthController::class, 'adminLogin']);
        
        // Password Reset for Admin
        Route::get('forgot-password', [PasswordResetController::class, 'showAdminForgotPassword'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendAdminResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showAdminReset'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'resetAdminPassword'])->name('password.update');
    });
    
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'adminLogout'])->name('logout');
    });
});
