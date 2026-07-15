<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiIntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('api_integrations')) {
                // Cache configuration to reduce database queries
                $integrations = \Illuminate\Support\Facades\Cache::remember('api_integrations.active', 3600, function () {
                    return \App\Models\ApiIntegration::where('is_active', true)->get();
                });

                foreach ($integrations as $integration) {
                    $config = $integration->config;
                    if (!is_array($config)) {
                        continue;
                    }

                    switch ($integration->provider) {
                        case 'midtrans':
                            if (isset($config['server_key'])) config(['services.midtrans.server_key' => $config['server_key']]);
                            if (isset($config['client_key'])) config(['services.midtrans.client_key' => $config['client_key']]);
                            if (isset($config['sandbox'])) config(['services.midtrans.sandbox' => filter_var($config['sandbox'], FILTER_VALIDATE_BOOLEAN)]);
                            break;

                        case 'google_oauth':
                            if (isset($config['client_id'])) config(['services.google.client_id' => $config['client_id']]);
                            if (isset($config['client_secret'])) config(['services.google.client_secret' => $config['client_secret']]);
                            if (isset($config['redirect'])) config(['services.google.redirect' => $config['redirect']]);
                            break;

                        case 'smtp':
                            if (isset($config['host'])) config(['mail.mailers.smtp.host' => $config['host']]);
                            if (isset($config['port'])) config(['mail.mailers.smtp.port' => $config['port']]);
                            if (isset($config['username'])) config(['mail.mailers.smtp.username' => $config['username']]);
                            if (isset($config['password'])) config(['mail.mailers.smtp.password' => $config['password']]);
                            if (isset($config['encryption'])) config(['mail.mailers.smtp.encryption' => $config['encryption']]);
                            if (isset($config['from_address'])) config(['mail.from.address' => $config['from_address']]);
                            if (isset($config['from_name'])) config(['mail.from.name' => $config['from_name']]);
                            break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Terjadi sebelum migrate atau DB tidak tersedia
            \Illuminate\Support\Facades\Log::warning('ApiIntegrationServiceProvider failed to load: ' . $e->getMessage());
        }
    }
}
