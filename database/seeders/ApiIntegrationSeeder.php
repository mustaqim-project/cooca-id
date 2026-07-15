<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ApiIntegration::firstOrCreate(
            ['provider' => 'midtrans'],
            [
                'name' => 'Midtrans Payment Gateway',
                'config' => [
                    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxx'),
                    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-xxx'),
                    'sandbox' => env('MIDTRANS_IS_PRODUCTION', false) ? false : true,
                ],
                'is_active' => true,
            ]
        );

        \App\Models\ApiIntegration::firstOrCreate(
            ['provider' => 'google_oauth'],
            [
                'name' => 'Google OAuth 2.0',
                'config' => [
                    'client_id' => env('GOOGLE_CLIENT_ID', ''),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
                    'redirect' => env('APP_URL') . '/customer/auth/google/callback',
                ],
                'is_active' => true,
            ]
        );

        \App\Models\ApiIntegration::firstOrCreate(
            ['provider' => 'smtp'],
            [
                'name' => 'Email SMTP',
                'config' => [
                    'host' => env('MAIL_HOST', '127.0.0.1'),
                    'port' => env('MAIL_PORT', '2525'),
                    'username' => env('MAIL_USERNAME', ''),
                    'password' => env('MAIL_PASSWORD', ''),
                    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                    'from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                    'from_name' => env('MAIL_FROM_NAME', 'Example'),
                ],
                'is_active' => true,
            ]
        );
    }
}
