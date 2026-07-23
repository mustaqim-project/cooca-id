<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@cooca.id'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('super_admin');

        // 2. Affiliator
        $affiliator = Affiliator::updateOrCreate(
            ['email' => 'affiliator@cooca.id'],
            [
                'name' => 'Demo Affiliator',
                'password' => Hash::make('password123'),
            ]
        );
        $affiliator->assignRole('affiliator');

        // 3. Customer
        $customer = Customer::updateOrCreate(
            ['email' => 'customer@cooca.id'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password123'),
            ]
        );
        $customer->assignRole('customer');
    }
}
