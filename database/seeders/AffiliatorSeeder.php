<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Hash;

final class AffiliatorSeeder extends Seeder
{
    public function run(): void
    {
        $affiliators = [
            [
                'name' => 'Siti Rahmawati (Top Affiliator)',
                'email' => 'affiliate@cooca.id',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'balance' => 4750000.00,
                'bank_name' => 'BCA',
                'bank_account' => '8271928371',
                'referral_code' => 'COOCA123',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@affiliate.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'balance' => 1250000.00,
                'bank_name' => 'Mandiri',
                'bank_account' => '1420019283712',
                'referral_code' => 'RIZKYPRO',
            ],
        ];

        foreach ($affiliators as $data) {
            $affiliator = Affiliator::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $affiliator->assignRole('affiliator');
        }

        echo "✅ Affiliator users successfully seeded.\n";
    }
}
