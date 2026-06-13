<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
    }

    private function bootObservers(): void
    {
        \App\Models\Transaction::observe(\App\Observers\TransactionObserver::class);
        \App\Models\Subscription::observe(\App\Observers\SubscriptionObserver::class);
        \App\Models\License::observe(\App\Observers\LicenseObserver::class);
        \App\Models\Customer::observe(\App\Observers\CustomerObserver::class);
        \App\Models\Affiliator::observe(\App\Observers\AffiliatorObserver::class);
    }
}
