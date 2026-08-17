<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyBankAccount;

final class CompanyBankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'bank_code' => '014',
                'account_number' => '8830-8899-8800',
                'account_holder' => 'PT COOCA TECHNOLOGIES INDONESIA',
                'branch' => 'KCP Sudirman Jakarta',
                'instructions' => 'Transfer tepat sesuai nominal tagihan invoice Anda.',
                'is_active' => true,
                'is_primary' => true,
                'sort_order' => 1,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'bank_code' => '008',
                'account_number' => '123-00-998877-6',
                'account_holder' => 'PT COOCA TECHNOLOGIES INDONESIA',
                'branch' => 'KC Jakarta Thamrin',
                'instructions' => 'Gunakan nomor invoice sebagai berita transfer jika memungkinkan.',
                'is_active' => true,
                'is_primary' => false,
                'sort_order' => 2,
            ],
            [
                'bank_name' => 'Bank Syariah Indonesia (BSI)',
                'bank_code' => '451',
                'account_number' => '711-2233-445',
                'account_holder' => 'PT COOCA TECHNOLOGIES INDONESIA',
                'branch' => 'KCP Kuningan',
                'instructions' => 'Khusus pembayaran via rekening syariah / BSI Mobile.',
                'is_active' => true,
                'is_primary' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($accounts as $acc) {
            CompanyBankAccount::updateOrCreate(
                ['account_number' => $acc['account_number']],
                $acc
            );
        }

        echo "✅ Company Bank Accounts successfully seeded.\n";
    }
}
