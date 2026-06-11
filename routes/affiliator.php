<?php

declare(strict_types=1);

use App\Http\Controllers\Affiliator\DashboardController;
use App\Http\Controllers\Affiliator\ReferralController;
use App\Http\Controllers\Affiliator\CommissionController;
use App\Http\Controllers\Affiliator\DownlineController;
use App\Http\Controllers\Affiliator\WithdrawalController;
use App\Http\Controllers\Affiliator\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Affiliator Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes are for the affiliator dashboard, protected by auth:affiliator middleware.
| All affiliator routes use Inertia.js for Vue 3 integration.
|
*/

Route::prefix('affiliator')->name('affiliator.')->middleware(['auth:affiliator'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Referrals Management
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/stats', [ReferralController::class, 'stats'])->name('referrals.stats');
    Route::get('/referrals/{customer}', [ReferralController::class, 'show'])->name('referrals.show');

    // Commissions Tracking
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/stats', [CommissionController::class, 'stats'])->name('commissions.stats');
    Route::get('/commissions/{commission}', [CommissionController::class, 'show'])->name('commissions.show');
    Route::get('/commissions/export', [CommissionController::class, 'export'])->name('commissions.export');

    // Downline Network (2-tier)
    Route::get('/downlines', [DownlineController::class, 'index'])->name('downlines.index');
    Route::get('/downlines/tree', [DownlineController::class, 'tree'])->name('downlines.tree');
    Route::get('/downlines/{affiliator}', [DownlineController::class, 'show'])->name('downlines.show');
    Route::get('/downlines/stats', [DownlineController::class, 'stats'])->name('downlines.stats');

    // Withdrawals Requests
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::get('/withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::get('/withdrawals/history', [WithdrawalController::class, 'history'])->name('withdrawals.history');

    // Reviews (from customers about products via affiliate link)
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my_reviews');

    // Profile Settings
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::put('/profile/bank-account', 'ProfileController@updateBankAccount')->name('profile.bank_account.update');
    Route::put('/profile/password', 'ProfileController@updatePassword')->name('profile.password.update');

    // Marketing Materials
    Route::get('/marketing-materials', 'MarketingController@index')->name('marketing_materials.index');
    Route::get('/marketing-materials/banners', 'MarketingController@banners')->name('marketing_materials.banners');
    Route::get('/marketing-materials/links', 'MarketingController@links')->name('marketing_materials.links');
});