<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use Illuminate\Support\Str;

class AdminAffiliatorCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin account
        Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
            ]
        );

        // Create affiliator account
        Affiliator::firstOrCreate(
            ['email' => 'affiliator@example.com'],
            [
                'name' => 'Affiliator User',
                'password' => bcrypt('password123'),
                'referral_code' => Str::random(10),
            ]
        );

        // Create customer account
        Customer::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => bcrypt('password123'),
            ]
        );
    }
}
