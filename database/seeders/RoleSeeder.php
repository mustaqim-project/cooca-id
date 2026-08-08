<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Guards: admin, customer, affiliator
        $guards = ['admin', 'customer', 'affiliator'];

        $rolesByGuard = [
            'admin' => ['super_admin', 'admin', 'support_admin', 'finance_admin'],
            'customer' => ['customer', 'customer_manager', 'customer_staff'],
            'affiliator' => ['affiliator', 'top_affiliator'],
        ];

        foreach ($rolesByGuard as $guard => $roles) {
            foreach ($roles as $roleName) {
                Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);
            }
        }

        echo "✅ Roles Spatie RBAC successfully seeded.\n";
    }
}
