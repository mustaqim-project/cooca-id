<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Per doc 08-security-rbac: 6 roles across 3 guards
        $guardPermissions = [
            'admin' => [
                // Trial management
                'trials.view',
                'trials.approve',
                'trials.reject',
                // Provisioning
                'provisioning.view',
                'provisioning.trigger',
                'provisioning.retry',
                // Subscription management
                'subscriptions.view',
                'subscriptions.manage',
                // Invoice/Payment
                'invoices.view',
                'payments.view',
                'payments.verify',
                // Affiliate management
                'affiliators.view',
                'affiliators.manage',
                'affiliators.suspend',
                'commissions.view',
                'commissions.approve',
                'withdrawals.view',
                'withdrawals.approve',
                // Customer management
                'customers.view',
                'customers.manage',
                // Product/Catalog
                'products.view',
                'products.create',
                'products.edit',
                'products.delete',
                'plans.view',
                'plans.create',
                'plans.edit',
                'plans.delete',
                // Domain/License
                'domains.view',
                'domains.manage',
                'licenses.view',
                'licenses.revoke',
                'licenses.reissue',
                // CMS
                'cms.pages.view',
                'cms.pages.manage',
                'cms.blog.view',
                'cms.blog.manage',
                'cms.landing.view',
                'cms.landing.manage',
                // Ticketing
                'tickets.view',
                'tickets.respond',
                'tickets.close',
                // Notification
                'notifications.view',
                'notifications.manage',
                'notifications.templates',
                // Settings & System
                'settings.view',
                'settings.manage',
                'audit_logs.view',
                'rbac.manage',
                // Email campaigns
                'email_campaigns.view',
                'email_campaigns.manage',
                // Vouchers
                'vouchers.view',
                'vouchers.create',
                'vouchers.manage',
                // Reviews
                'reviews.view',
                'reviews.moderate',
            ],
            'customer' => [
                // Profile
                'profile.view',
                'profile.edit',
                'company.view',
                'company.edit',
                // Catalog
                'catalog.browse',
                // Trial
                'trials.request',
                'trials.view_own',
                // Subscription
                'subscriptions.view_own',
                'subscriptions.renew',
                'subscriptions.cancel',
                // Invoice/Payment
                'invoices.view_own',
                'invoices.pay',
                'invoices.download',
                // Domain
                'domains.view_own',
                'domains.request',
                // License
                'licenses.view_own',
                // Ticketing
                'tickets.create',
                'tickets.view_own',
                'tickets.reply',
                // Review
                'reviews.create',
                // Staff management (Customer Manager only)
                'staff.view',
                'staff.manage',
            ],
            'affiliator' => [
                // Profile
                'profile.view',
                'profile.edit',
                // Referrals
                'referrals.view',
                'referrals.share',
                // Commissions
                'commissions.view_own',
                // Withdrawals
                'withdrawals.request',
                'withdrawals.view_own',
                // Downline
                'downlines.view',
                // Marketing
                'marketing.view',
                // Reviews
                'reviews.create',
                // Tickets
                'tickets.create',
                'tickets.view_own',
                'tickets.reply',
            ],
        ];

        // Create permissions per guard
        foreach ($guardPermissions as $guard => $permissions) {
            foreach ($permissions as $perm) {
                Permission::findOrCreate($perm, $guard);
            }
        }

        // Create roles and assign permissions
        // Admin guard roles
        $superAdmin = Role::findOrCreate('super_admin', 'admin');
        $superAdmin->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        $admin = Role::findOrCreate('admin', 'admin');
        $admin->givePermissionTo(
            Permission::where('guard_name', 'admin')
                ->whereNotIn('name', ['rbac.manage', 'settings.manage'])
                ->get()
        );

        // Customer guard roles
        $customer = Role::findOrCreate('customer', 'customer');
        $customer->givePermissionTo(
            Permission::where('guard_name', 'customer')
                ->whereNotIn('name', ['staff.view', 'staff.manage'])
                ->get()
        );

        $customerManager = Role::findOrCreate('customer_manager', 'customer');
        $customerManager->givePermissionTo(Permission::where('guard_name', 'customer')->get());

        $customerStaff = Role::findOrCreate('customer_staff', 'customer');
        $customerStaff->givePermissionTo(
            Permission::where('guard_name', 'customer')
                ->whereIn('name', [
                    'profile.view',
                    'catalog.browse',
                    'subscriptions.view_own',
                    'invoices.view_own',
                    'licenses.view_own',
                    'tickets.create',
                    'tickets.view_own',
                    'tickets.reply',
                ])
                ->get()
        );

        // Affiliator guard role
        $affiliator = Role::findOrCreate('affiliator', 'affiliator');
        $affiliator->givePermissionTo(Permission::where('guard_name', 'affiliator')->get());
    }
}
