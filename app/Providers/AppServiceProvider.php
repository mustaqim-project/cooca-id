<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    AdminRepositoryInterface,
    AffiliateCommissionRepositoryInterface,
    AffiliatorRepositoryInterface,
    CustomerRepositoryInterface,
    LicenseRepositoryInterface,
    ProductRepositoryInterface,
    SubscriptionRepositoryInterface,
    TransactionRepositoryInterface,
    VoucherRepositoryInterface,
};
use App\Repositories\Eloquent\{
    AdminRepository,
    AffiliateCommissionRepository,
    AffiliatorRepository,
    CustomerRepository,
    LicenseRepository,
    ProductRepository,
    SubscriptionPlanRepository,
    SubscriptionRepository,
    TransactionRepository,
    VoucherRepository,
};
use App\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use App\Repositories\Contracts\VoucherUsageRepositoryInterface;
use App\Repositories\Eloquent\VoucherUsageRepository;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AffiliateCommissionRepositoryInterface::class, AffiliateCommissionRepository::class);
        $this->app->bind(AffiliatorRepositoryInterface::class, AffiliatorRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(LicenseRepositoryInterface::class, LicenseRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(SubscriptionPlanRepositoryInterface::class, SubscriptionPlanRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->bind(VoucherUsageRepositoryInterface::class, VoucherUsageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
