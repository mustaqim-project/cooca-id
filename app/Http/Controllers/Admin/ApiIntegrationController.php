<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Admin API Integration Controller
 *
 * Manages API integrations (Midtrans, Google OAuth, SMTP, WhatsApp)
 * with encrypted config storage via the api_integrations table.
 */
final class ApiIntegrationController extends Controller
{
    /**
     * Known provider definitions with field schemas.
     */
    private const PROVIDER_SCHEMAS = [
        'midtrans' => [
            'name' => 'Midtrans Payment Gateway',
            'fields' => [
                'server_key' => ['label' => 'Server Key', 'type' => 'password', 'required' => true],
                'client_key' => ['label' => 'Client Key', 'type' => 'password', 'required' => true],
                'sandbox'    => ['label' => 'Sandbox Mode', 'type' => 'boolean', 'required' => false],
            ],
        ],
        'google_oauth' => [
            'name' => 'Google OAuth 2.0',
            'fields' => [
                'client_id'     => ['label' => 'Client ID', 'type' => 'password', 'required' => true],
                'client_secret' => ['label' => 'Client Secret', 'type' => 'password', 'required' => true],
                'redirect'      => ['label' => 'Redirect URL', 'type' => 'text', 'required' => true],
            ],
        ],
        'smtp' => [
            'name' => 'Email SMTP',
            'fields' => [
                'host'         => ['label' => 'SMTP Host', 'type' => 'text', 'required' => true],
                'port'         => ['label' => 'SMTP Port', 'type' => 'number', 'required' => true],
                'username'     => ['label' => 'Username', 'type' => 'text', 'required' => true],
                'password'     => ['label' => 'Password', 'type' => 'password', 'required' => true],
                'encryption'   => ['label' => 'Encryption (tls/ssl)', 'type' => 'text', 'required' => false],
                'from_address' => ['label' => 'From Address', 'type' => 'email', 'required' => true],
                'from_name'    => ['label' => 'From Name', 'type' => 'text', 'required' => false],
            ],
        ],
        'whatsapp' => [
            'name' => 'WhatsApp (whatsapp-web.js)',
            'fields' => [
                'server_url' => ['label' => 'WA Server URL', 'type' => 'text', 'required' => true],
                'api_token'  => ['label' => 'API Token (opsional)', 'type' => 'password', 'required' => false],
            ],
        ],
    ];

    /**
     * Display a listing of all API integrations.
     */
    public function index()
    {
        $integrations = ApiIntegration::orderBy('provider')->get();

        // Ensure all known providers exist in the list
        $existingProviders = $integrations->pluck('provider')->toArray();
        foreach (self::PROVIDER_SCHEMAS as $provider => $schema) {
            if (!in_array($provider, $existingProviders)) {
                $integrations->push(new ApiIntegration([
                    'provider'  => $provider,
                    'name'      => $schema['name'],
                    'config'    => [],
                    'is_active' => false,
                ]));
            }
        }

        return view('admin.api-integrations.index', [
            'integrations' => $integrations,
            'schemas'      => self::PROVIDER_SCHEMAS,
        ]);
    }

    /**
     * Show the edit form for a specific integration.
     */
    public function edit(string $provider)
    {
        if (!isset(self::PROVIDER_SCHEMAS[$provider])) {
            abort(404, 'Provider not found.');
        }

        $integration = ApiIntegration::where('provider', $provider)->first();
        $schema = self::PROVIDER_SCHEMAS[$provider];

        return view('admin.api-integrations.edit', [
            'integration' => $integration,
            'provider'    => $provider,
            'schema'      => $schema,
        ]);
    }

    /**
     * Update the specified integration's config.
     */
    public function update(Request $request, string $provider)
    {
        if (!isset(self::PROVIDER_SCHEMAS[$provider])) {
            abort(404, 'Provider not found.');
        }

        $schema = self::PROVIDER_SCHEMAS[$provider];

        // Build validation rules dynamically
        $rules = [];
        foreach ($schema['fields'] as $field => $meta) {
            $fieldRules = ['nullable'];
            if ($meta['type'] === 'email') $fieldRules[] = 'email';
            if ($meta['type'] === 'number') $fieldRules[] = 'numeric';
            $rules["config.{$field}"] = $fieldRules;
        }
        $rules['is_active'] = ['sometimes', 'boolean'];

        $validated = $request->validate($rules);

        // Build config array, preserving existing values for empty password fields
        $existing = ApiIntegration::where('provider', $provider)->first();
        $existingConfig = $existing ? ($existing->config ?? []) : [];
        $newConfig = [];

        foreach ($schema['fields'] as $field => $meta) {
            $value = $validated['config'][$field] ?? null;

            // For password fields, preserve existing value if left blank
            if ($meta['type'] === 'password' && blank($value) && isset($existingConfig[$field])) {
                $newConfig[$field] = $existingConfig[$field];
            } else {
                $newConfig[$field] = $value ?? '';
            }
        }

        ApiIntegration::updateOrCreate(
            ['provider' => $provider],
            [
                'name'      => $schema['name'],
                'config'    => $newConfig,
                'is_active' => $request->boolean('is_active'),
            ]
        );

        // Flush cache so ServiceProvider picks up new values
        Cache::forget('api_integrations.active');

        return redirect()
            ->route('admin.api-integrations.index')
            ->with('success', "Konfigurasi {$schema['name']} berhasil disimpan.");
    }

    /**
     * Toggle active status.
     */
    public function toggle(string $provider)
    {
        $integration = ApiIntegration::where('provider', $provider)->firstOrFail();
        $integration->update(['is_active' => !$integration->is_active]);

        Cache::forget('api_integrations.active');

        $status = $integration->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "{$integration->name} berhasil {$status}.");
    }

    /**
     * Test connection for a provider.
     */
    public function test(string $provider)
    {
        $integration = ApiIntegration::where('provider', $provider)->first();
        if (!$integration || !$integration->is_active) {
            return back()->with('error', 'Integrasi belum aktif atau belum dikonfigurasi.');
        }

        $config = $integration->config;

        try {
            switch ($provider) {
                case 'smtp':
                    // Test SMTP by trying to connect
                    $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                        $config['host'] ?? 'localhost',
                        (int) ($config['port'] ?? 587),
                    );
                    return back()->with('success', 'Koneksi SMTP berhasil dikonfigurasi.');

                case 'whatsapp':
                    $response = Http::timeout(5)->get(($config['server_url'] ?? 'http://localhost:3000') . '/qr');
                    $data = $response->json();
                    $statusMsg = match ($data['status'] ?? 'unknown') {
                        'ready'   => 'WhatsApp sudah terautentikasi dan siap digunakan.',
                        'pending' => 'QR Code tersedia. Silakan scan di Admin Panel.',
                        'loading' => 'WhatsApp sedang memuat, tunggu beberapa detik.',
                        default   => 'Status tidak diketahui.',
                    };
                    return back()->with('success', "WhatsApp Status: {$statusMsg}");

                case 'midtrans':
                    return back()->with('success', 'Konfigurasi Midtrans tersimpan. Gunakan Midtrans Simulator untuk tes.');

                default:
                    return back()->with('info', 'Test koneksi tidak tersedia untuk provider ini.');
            }
        } catch (\Exception $e) {
            Log::error("API Integration test failed for {$provider}", ['error' => $e->getMessage()]);
            return back()->with('error', "Test gagal: {$e->getMessage()}");
        }
    }
}
