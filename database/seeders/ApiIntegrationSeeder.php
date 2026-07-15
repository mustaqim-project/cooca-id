<?php

namespace Database\Seeders;

use App\Models\ApiIntegration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $integrations = [
            [
                'name' => 'fonnte',
                'label' => 'Fonnte WhatsApp',
                'category' => 'messaging',
                'description' => 'WhatsApp messaging service via Fonnte API. Reads from FONNTE_API_KEY and FONNTE_API_URL in .env',
                'credentials' => [
                    'api_key' => config('services.fonnte.api_key', ''),
                    'api_url' => config('services.fonnte.api_url', 'https://api.fonnte.com/send'),
                ],
                'config' => [
                    'timeout' => 30,
                    'retry_count' => 3,
                ],
                'is_active' => !empty(config('services.fonnte.api_key')),
            ],
            [
                'name' => 'smtp',
                'label' => 'SMTP Mail Server',
                'category' => 'email',
                'description' => 'SMTP configuration for sending emails. Reads from MAIL_* variables in .env',
                'credentials' => [
                    'host' => config('mail.mailers.smtp.host', ''),
                    'port' => config('mail.mailers.smtp.port', 587),
                    'username' => config('mail.mailers.smtp.username', ''),
                    'password' => config('mail.mailers.smtp.password', ''),
                    'encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                    'from_address' => config('mail.from.address', ''),
                    'from_name' => config('mail.from.name', ''),
                ],
                'config' => [
                    'timeout' => 30,
                ],
                'is_active' => !empty(config('mail.mailers.smtp.host')),
            ],
            [
                'name' => 'google_oauth',
                'label' => 'Google OAuth',
                'category' => 'authentication',
                'description' => 'Google OAuth 2.0 for user authentication. Reads from GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env',
                'credentials' => [
                    'client_id' => config('services.google.client_id', ''),
                    'client_secret' => config('services.google.client_secret', ''),
                    'redirect_uri' => config('services.google.redirect_uri', ''),
                ],
                'config' => [
                    'scopes' => ['email', 'profile'],
                ],
                'is_active' => !empty(config('services.google.client_id')),
            ],
            [
                'name' => 'midtrans',
                'label' => 'Midtrans Payment Gateway',
                'category' => 'payment',
                'description' => 'Payment gateway for processing transactions. Reads from MIDTRANS_* variables in .env',
                'credentials' => [
                    'server_key' => config('services.midtrans.server_key', ''),
                    'client_key' => config('services.midtrans.client_key', ''),
                    'is_production' => config('services.midtrans.is_production', false),
                ],
                'config' => [
                    'currency' => 'IDR',
                ],
                'is_active' => !empty(config('services.midtrans.server_key')),
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($integrations as $data) {
                ApiIntegration::updateOrCreate(
                    ['name' => $data['name']],
                    $data
                );
            }
            DB::commit();
            $this->command->info('API Integrations seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed API Integrations: ' . $e->getMessage());
        }
    }
}
