<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\CompanyProfile;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Hash;

final class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $affiliator = Affiliator::where('referral_code', 'COOCA123')->first();

        $customers = [
            [
                'name' => 'Budi Santoso (Customer Demo)',
                'email' => 'customer@cooca.id',
                'phone' => '082114468467',
                'password' => Hash::make('password123'),
                'business_name' => 'PT Resto Sukses Abadi',
                'domain' => 'resto-sukses.cooca.id',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'affiliator_id' => $affiliator?->id,
            ],
            [
                'name' => 'Dr. Farhan Kamil',
                'email' => 'farhan@klinikmedika.com',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'business_name' => 'Klinik Utama Farhan Medika',
                'domain' => 'farhan-medika.cooca.id',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'affiliator_id' => null,
            ],
            [
                'name' => 'Rahmat Hidayat',
                'email' => 'rahmat@rahmatbengkel.com',
                'phone' => '085712345678',
                'password' => Hash::make('password123'),
                'business_name' => 'Bengkel Rahmat Auto Care',
                'domain' => 'rahmat-auto.cooca.id',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'affiliator_id' => $affiliator?->id,
            ],
        ];

        foreach ($customers as $data) {
            $customer = Customer::where('email', $data['email'])
                ->orWhere('phone', $data['phone'])
                ->first();

            if (!$customer) {
                $customer = Customer::create($data);
            } else {
                $customer->update([
                    'name' => $data['name'],
                    'business_name' => $data['business_name'],
                    'domain' => $data['domain'],
                    'status' => 'active',
                ]);
            }

            $customer->assignRole('customer');

            CompanyProfile::firstOrCreate(
                ['customer_id' => $customer->id],
                [
                    'company_name' => $customer->business_name ?? 'Company',
                    'industry' => 'Enterprise SaaS',
                    'company_size' => '11-50',
                    'phone' => $customer->phone,
                    'address' => 'Jl. Jendral Sudirman No. 88, Jakarta Selatan',
                    'city' => 'Jakarta Selatan',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12190',
                    'npwp' => '01.234.567.8-012.000',
                    'website' => 'https://' . ($customer->domain ?? 'cooca.id'),
                ]
            );
        }

        echo "✅ Customer Accounts & Company Profiles successfully seeded.\n";
    }
}
