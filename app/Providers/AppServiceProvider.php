<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Repositories\Contracts\{
    AdminRepositoryInterface,
    AffiliateCommissionRepositoryInterface,
    AffiliatorRepositoryInterface,
    CustomerRepositoryInterface,
    InvoiceRepositoryInterface,
    LicenseRepositoryInterface,
    NotificationTemplateRepositoryInterface,
    ProductRepositoryInterface,
    SubscriptionPlanRepositoryInterface,
    SubscriptionRepositoryInterface,
    TransactionRepositoryInterface,
    VoucherRepositoryInterface,
    VoucherUsageRepositoryInterface,
};
use App\Repositories\Eloquent\{
    AdminRepository,
    AffiliateCommissionRepository,
    AffiliatorRepository,
    CustomerRepository,
    InvoiceRepository,
    LicenseRepository,
    NotificationTemplateRepository,
    ProductRepository,
    SubscriptionPlanRepository,
    SubscriptionRepository,
    TransactionRepository,
    VoucherRepository,
    VoucherUsageRepository,
};
use App\Models\{
    License,
    Transaction,
    AffiliateCommission,
    AffiliateWithdrawal,
    Customer,
    Affiliator,
    Voucher,
    Subscription,
    Invoice,
    ErpRequest,
    Domain,
    Ticket,
    Review,
};
use App\Policies\{
    LicensePolicy,
    TransactionPolicy,
    AffiliateCommissionPolicy,
    AffiliateWithdrawalPolicy,
    CustomerPolicy,
    AffiliatorPolicy,
    VoucherPolicy,
    SubscriptionPolicy,
    InvoicePolicy,
    ErpRequestPolicy,
    DomainPolicy,
    TicketPolicy,
    ReviewPolicy,
};

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $bindings = [
            AdminRepositoryInterface::class => AdminRepository::class,
            AffiliateCommissionRepositoryInterface::class => AffiliateCommissionRepository::class,
            AffiliatorRepositoryInterface::class => AffiliatorRepository::class,
            CustomerRepositoryInterface::class => CustomerRepository::class,
            InvoiceRepositoryInterface::class => InvoiceRepository::class,
            LicenseRepositoryInterface::class => LicenseRepository::class,
            NotificationTemplateRepositoryInterface::class => NotificationTemplateRepository::class,
            ProductRepositoryInterface::class => ProductRepository::class,
            SubscriptionPlanRepositoryInterface::class => SubscriptionPlanRepository::class,
            SubscriptionRepositoryInterface::class => SubscriptionRepository::class,
            TransactionRepositoryInterface::class => TransactionRepository::class,
            VoucherRepositoryInterface::class => VoucherRepository::class,
            VoucherUsageRepositoryInterface::class => VoucherUsageRepository::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootPolicies();
        $this->bootObservers();
        $this->bootRateLimiters();

        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'customer' => \App\Models\Customer::class,
            'affiliator' => \App\Models\Affiliator::class,
        ]);
    }

    private function bootPolicies(): void
    {
        Gate::policy(License::class, LicensePolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(AffiliateCommission::class, AffiliateCommissionPolicy::class);
        Gate::policy(AffiliateWithdrawal::class, AffiliateWithdrawalPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Affiliator::class, AffiliatorPolicy::class);
        Gate::policy(Voucher::class, VoucherPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(ErpRequest::class, ErpRequestPolicy::class);
        Gate::policy(Domain::class, DomainPolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
    }

    private function bootObservers(): void
    {
        \App\Models\Transaction::observe(\App\Observers\TransactionObserver::class);
        \App\Models\Subscription::observe(\App\Observers\SubscriptionObserver::class);
        \App\Models\License::observe(\App\Observers\LicenseObserver::class);
        \App\Models\Customer::observe(\App\Observers\CustomerObserver::class);
        \App\Models\Affiliator::observe(\App\Observers\AffiliatorObserver::class);
    }

    /**
     * Configure rate limiters for API and authentication endpoints.
     */
    private function bootRateLimiters(): void
    {
        // Customer login rate limiter: 5 attempts per 15 minutes window (brute force protection)
        RateLimiter::for('customer-login', function (Request $request) {
            $key = $request->ip() . '|' . strtolower((string) $request->input('email', ''));
            return Limit::perMinutes(15, 5)->by($key)
                ->response(function ($request, $headers) {
                    \App\Models\BlockedIp::updateOrCreate(
                        ['ip_address' => $request->ip()],
                        [
                            'reason' => 'Brute force attack on customer login',
                            'blocked_until' => now()->addMinutes(15),
                        ]
                    );
                    
                    \Illuminate\Support\Facades\Cache::forget("blocked_ip_{$request->ip()}");

                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Terlalu banyak percobaan login yang gagal (5x salah password). Akses login dibatasi selama 15 menit.',
                        ], 429, $headers);
                    }

                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan login yang salah (5x). Akses login Anda dibatasi sementara selama 15 menit.',
                    ])->withInput($request->only('email'));
                });
        });

        // Customer registration rate limiter: 10 requests per minute
        RateLimiter::for('customer-register', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests. Please try again later.',
                    ], 429, $headers);
                });
        });

        // Admin routes rate limiter: 120 requests per minute
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?? $request->ip());
        });

        // Admin login rate limiter: 5 attempts per 15 minutes window (brute force protection)
        RateLimiter::for('admin-login', function (Request $request) {
            $key = $request->ip() . '|' . strtolower((string) $request->input('email', ''));
            return Limit::perMinutes(15, 5)->by($key)
                ->response(function ($request, $headers) {
                    \App\Models\BlockedIp::updateOrCreate(
                        ['ip_address' => $request->ip()],
                        [
                            'reason' => 'Brute force attack on admin login',
                            'blocked_until' => now()->addMinutes(15),
                        ]
                    );
                    
                    \Illuminate\Support\Facades\Cache::forget("blocked_ip_{$request->ip()}");

                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Terlalu banyak percobaan login yang gagal (5x salah password). Akses login dibatasi selama 15 menit.',
                        ], 429, $headers);
                    }

                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan login yang salah (5x). Akses login Anda dibatasi sementara selama 15 menit.',
                    ])->withInput($request->only('email'));
                });
        });

        // Affiliator login rate limiter: 5 attempts per 15 minutes window
        RateLimiter::for('affiliator-login', function (Request $request) {
            $key = $request->ip() . '|' . strtolower((string) $request->input('email', ''));
            return Limit::perMinutes(15, 5)->by($key)
                ->response(function ($request, $headers) {
                    \App\Models\BlockedIp::updateOrCreate(
                        ['ip_address' => $request->ip()],
                        [
                            'reason' => 'Brute force attack on affiliator login',
                            'blocked_until' => now()->addMinutes(15),
                        ]
                    );
                    
                    \Illuminate\Support\Facades\Cache::forget("blocked_ip_{$request->ip()}");

                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Terlalu banyak percobaan login yang gagal (5x salah password). Akses login dibatasi selama 15 menit.',
                        ], 429, $headers);
                    }

                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan login yang salah (5x). Akses login Anda dibatasi sementara selama 15 menit.',
                    ])->withInput($request->only('email'));
                });
        });

        // General API rate limiter: 60 requests per minute
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests',
                    ], 429, $headers);
                });
        });

        // Login rate limiter: 5 attempts per 15 minutes window
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(15, 5)->by($request->ip())
                ->response(function ($request, $headers) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Too Many Requests. Lockout 15 minutes.',
                        ], 429, $headers);
                    }
                    return back()->withErrors(['email' => 'Terlalu banyak percobaan login yang salah. Coba lagi dalam 15 menit.']);
                });
        });

        // Registration rate limiter: 10 requests per minute
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests. Please try again later.',
                    ], 429, $headers);
                });
        });

        // Customer routes rate limiter: 60 requests per minute
        RateLimiter::for('customer', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?? $request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests',
                    ], 429, $headers);
                });
        });

        // Affiliator routes rate limiter: 60 requests per minute
        RateLimiter::for('affiliator', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?? $request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests',
                    ], 429, $headers);
                });
        });

        // Midtrans webhook rate limiter: 120 requests per minute (higher for webhook processing)
        RateLimiter::for('midtrans-webhook', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'message' => 'Too Many Requests',
                    ], 429, $headers);
                });
        });
    }
}


