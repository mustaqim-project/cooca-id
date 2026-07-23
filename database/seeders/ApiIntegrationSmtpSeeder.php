<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApiIntegration;

class ApiIntegrationSmtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Noreply
        ApiIntegration::updateOrCreate(
            ['provider' => 'smtp_noreply'],
            [
                'name' => 'SMTP Noreply (Auth)',
                'is_active' => true,
                'config' => [
                    'host' => 'smtp.mailtrap.io', // Sesuaikan
                    'port' => 2525, // Sesuaikan
                    'username' => 'your_username', // Sesuaikan
                    'password' => 'your_password', // Sesuaikan
                    'encryption' => 'tls',
                    'from_address' => 'noreply@cooca.id',
                    'from_name' => 'Cooca.id'
                ]
            ]
        );

        // 2. Support
        ApiIntegration::updateOrCreate(
            ['provider' => 'smtp_support'],
            [
                'name' => 'SMTP Support (Tickets)',
                'is_active' => true,
                'config' => [
                    'host' => 'smtp.mailtrap.io',
                    'port' => 2525,
                    'username' => 'your_username',
                    'password' => 'your_password',
                    'encryption' => 'tls',
                    'from_address' => 'support@cooca.id',
                    'from_name' => 'Cooca Support'
                ]
            ]
        );

        // 3. Billing
        ApiIntegration::updateOrCreate(
            ['provider' => 'smtp_billing'],
            [
                'name' => 'SMTP Billing (Invoice)',
                'is_active' => true,
                'config' => [
                    'host' => 'smtp.mailtrap.io',
                    'port' => 2525,
                    'username' => 'your_username',
                    'password' => 'your_password',
                    'encryption' => 'tls',
                    'from_address' => 'billing@cooca.id',
                    'from_name' => 'Cooca Billing'
                ]
            ]
        );

        // 4. Marketing
        ApiIntegration::updateOrCreate(
            ['provider' => 'smtp_marketing'],
            [
                'name' => 'SMTP Marketing',
                'is_active' => true,
                'config' => [
                    'host' => 'smtp.mailtrap.io',
                    'port' => 2525,
                    'username' => 'your_username',
                    'password' => 'your_password',
                    'encryption' => 'tls',
                    'from_address' => 'marketing@cooca.id',
                    'from_name' => 'Cooca Promo & Marketing'
                ]
            ]
        );
    }
}
