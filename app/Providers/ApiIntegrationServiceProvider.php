<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ApiIntegration;

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
     * Memuat konfigurasi API dari database ke dalam config Laravel.
     * Hasil diproses dan disimpan dalam cache sebagai array untuk kinerja maksimal.
     */
    public function boot(): void
    {
        try {
            // Cek apakah tabel ada (untuk menghindari error saat migrasi)
            if (!Schema::hasTable('api_integrations')) {
                return;
            }

            $cacheKey = 'api_integrations.processed_configs';
            $lockKey = $cacheKey . '_lock';
            $lock = Cache::lock($lockKey, 10);

            // Coba ambil dari cache
            $processedConfigs = Cache::get($cacheKey);

            // Jika cache tidak ada atau bukan array, kita refresh
            if (!is_array($processedConfigs)) {
                // Gunakan lock untuk mencegah multiple request melakukan hal yang sama
                if ($lock->get()) {
                    try {
                        // Ambil data dari database
                        $integrations = ApiIntegration::where('is_active', true)->get();

                        // Proses dan bangun array konfigurasi
                        $processedConfigs = $this->buildConfigArray($integrations);

                        // Simpan ke cache (sebagai array, bukan object)
                        Cache::put($cacheKey, $processedConfigs, 3600);

                        Log::info('ApiIntegration configs cached successfully.', ['count' => count($processedConfigs)]);
                    } finally {
                        $lock->release();
                    }
                } else {
                    // Jika tidak dapat lock, tunggu sebentar lalu coba baca lagi
                    usleep(100000); // 0.1 detik
                    $processedConfigs = Cache::get($cacheKey);
                    if (!is_array($processedConfigs)) {
                        // Fallback: ambil langsung dari DB tanpa cache
                        $integrations = ApiIntegration::where('is_active', true)->get();
                        $processedConfigs = $this->buildConfigArray($integrations);
                        Log::warning('Could not acquire lock, loaded configs directly from DB.');
                    }
                }
            }

            // Pastikan $processedConfigs adalah array
            if (!is_array($processedConfigs)) {
                Log::error('Processed configs is not an array after refresh.');
                return;
            }

            // Terapkan konfigurasi
            $this->applyConfigs($processedConfigs);
        } catch (\Exception $e) {
            Log::warning('ApiIntegrationServiceProvider failed to load: ' . $e->getMessage());
        }
    }

    /**
     * Membangun array konfigurasi dari koleksi ApiIntegration.
     *
     * @param \Illuminate\Support\Collection|iterable $integrations
     * @return array
     */
    private function buildConfigArray($integrations): array
    {
        $configs = [];

        if (!is_iterable($integrations)) {
            Log::error('Integrations is not iterable.', ['type' => gettype($integrations)]);
            return $configs;
        }

        foreach ($integrations as $integration) {
            // Defensive check: pastikan $integration adalah object/Model yang valid
            if (!is_object($integration) || !isset($integration->config)) {
                Log::warning('Skipping invalid integration record.', ['type' => gettype($integration)]);
                continue;
            }

            $config = $integration->config; // Sudah berupa array (dari accessor)

            if (!is_array($config)) {
                $integrationId = isset($integration->id) ? $integration->id : 'unknown';
                $provider = isset($integration->provider) ? $integration->provider : 'unknown';
                
                Log::error("Invalid config for integration ID {$integrationId}", [
                    'provider' => $provider,
                ]);
                continue;
            }

            $provider = isset($integration->provider) ? $integration->provider : 'unknown';

            // Simpan dalam bentuk yang mudah diolah nanti
            $configs[] = [
                'provider' => $provider,
                'config'   => $config,
            ];
        }

        return $configs;
    }

    /**
     * Menerapkan array konfigurasi ke dalam Laravel config.
     *
     * @param array $configs
     * @return void
     */
    private function applyConfigs(array $configs): void
    {
        foreach ($configs as $item) {
            if (!is_array($item) || !isset($item['provider']) || !isset($item['config'])) {
                continue; // Defensive check
            }

            $provider = $item['provider'];
            $config = $item['config'];

            switch ($provider) {
                case 'midtrans':
                    if (isset($config['server_key'])) {
                        config(['services.midtrans.server_key' => $config['server_key']]);
                    }
                    if (isset($config['client_key'])) {
                        config(['services.midtrans.client_key' => $config['client_key']]);
                    }
                    if (isset($config['sandbox'])) {
                        config(['services.midtrans.sandbox' => filter_var($config['sandbox'], FILTER_VALIDATE_BOOLEAN)]);
                    }
                    break;

                case 'google_oauth':
                    if (isset($config['client_id'])) {
                        config(['services.google.client_id' => $config['client_id']]);
                    }
                    if (isset($config['client_secret'])) {
                        config(['services.google.client_secret' => $config['client_secret']]);
                    }
                    if (isset($config['redirect'])) {
                        config(['services.google.redirect' => $config['redirect']]);
                    }
                    break;

                // Support multiple SMTP configurations
                case 'smtp':
                case 'smtp_noreply':
                case 'smtp_marketing':
                case 'smtp_support':
                case 'smtp_billing':
                    // Extract mailer name from provider (e.g., 'smtp_marketing' -> 'marketing')
                    // For legacy 'smtp', we'll map it to 'smtp' mailer.
                    $mailerName = $provider === 'smtp' ? 'smtp' : str_replace('smtp_', '', $provider);

                    $mailerConfig = [
                        'transport' => 'smtp',
                        'host'      => $config['host'] ?? env('MAIL_HOST', '127.0.0.1'),
                        'port'      => $config['port'] ?? env('MAIL_PORT', 2525),
                        'encryption'=> $config['encryption'] ?? env('MAIL_ENCRYPTION', 'tls'),
                        'username'  => $config['username'] ?? env('MAIL_USERNAME'),
                        'password'  => $config['password'] ?? env('MAIL_PASSWORD'),
                        'timeout'   => null,
                        'local_domain' => env('MAIL_EHLO_DOMAIN'),
                    ];

                    // Override the specific mailer config
                    config(["mail.mailers.{$mailerName}" => $mailerConfig]);

                    // If it's noreply (or legacy smtp), we set it as the default global mailer
                    // so built-in features like Reset Password will use it automatically.
                    if ($provider === 'smtp_noreply' || $provider === 'smtp') {
                        config(['mail.default' => $mailerName]);

                        if (isset($config['from_address'])) {
                            config(['mail.from.address' => $config['from_address']]);
                        }
                        if (isset($config['from_name'])) {
                            config(['mail.from.name' => $config['from_name']]);
                        }
                    }

                    // For all mailers, if they have specific 'from' settings, they can be stored 
                    // in their own configuration path, allowing the application to use it via 
                    // config("mail.mailers.{$mailerName}.from_address") if needed.
                    if (isset($config['from_address'])) {
                        config(["mail.mailers.{$mailerName}.from_address" => $config['from_address']]);
                    }
                    if (isset($config['from_name'])) {
                        config(["mail.mailers.{$mailerName}.from_name" => $config['from_name']]);
                    }
                    break;
            }
        }
    }
}
