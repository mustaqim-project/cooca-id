<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Administrator',
                'email' => 'admin@cooca.id',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Operational Admin',
                'email' => 'ops@cooca.id',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'admin',
            ],
            [
                'name' => 'Finance Admin',
                'email' => 'finance@cooca.id',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'finance_admin',
            ],
        ];

        foreach ($admins as $data) {
            $role = $data['role'];
            unset($data['role']);

            $admin = Admin::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            $admin->assignRole($role);
        }

        echo "✅ Admin Users successfully seeded.\n";
    }
}
