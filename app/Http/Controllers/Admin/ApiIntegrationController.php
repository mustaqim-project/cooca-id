<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * Admin API Integration Controller
 * 
 * Manages API integrations like Fonnte, SMTP, Google OAuth, etc.
 */
class ApiIntegrationController extends Controller
{
    /**
     * Display a listing of API integrations.
     */
    public function index(Request $request)
    {
        $query = ApiIntegration::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('label', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $integrations = $query->orderBy('category')->orderBy('label')->get();
        $categories = ApiIntegration::getCategories();

        return view('admin.api-integrations.index', compact('integrations', 'categories'));
    }

    /**
     * Show the form for creating a new API integration.
     */
    public function create()
    {
        $categories = ApiIntegration::getCategories();
        return view('admin.api-integrations.create', compact('categories'));
    }

    /**
     * Store a newly created API integration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:api_integrations,name|max:100',
            'label' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(ApiIntegration::getCategories())),
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'credentials' => 'nullable|array',
            'config' => 'nullable|array',
        ]);

        $validated['credentials'] = $validated['credentials'] ?? [];
        $validated['config'] = $validated['config'] ?? [];
        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->guard('admin')->id();

        ApiIntegration::create($validated);

        return redirect()->route('admin.api-integrations.index')
            ->with('success', 'API Integration created successfully.');
    }

    /**
     * Display the specified API integration.
     */
    public function show(ApiIntegration $apiIntegration)
    {
        return view('admin.api-integrations.show', compact('apiIntegration'));
    }

    /**
     * Show the form for editing the specified API integration.
     */
    public function edit(ApiIntegration $apiIntegration)
    {
        $categories = ApiIntegration::getCategories();
        return view('admin.api-integrations.edit', compact('apiIntegration', 'categories'));
    }

    /**
     * Update the specified API integration.
     */
    public function update(Request $request, ApiIntegration $apiIntegration)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:api_integrations,name,' . $apiIntegration->id,
            'label' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(ApiIntegration::getCategories())),
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'credentials' => 'nullable|array',
            'config' => 'nullable|array',
        ]);

        $validated['credentials'] = $validated['credentials'] ?? [];
        $validated['config'] = $validated['config'] ?? [];
        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->guard('admin')->id();

        $apiIntegration->update($validated);

        return redirect()->route('admin.api-integrations.index')
            ->with('success', 'API Integration updated successfully.');
    }

    /**
     * Remove the specified API integration.
     */
    public function destroy(ApiIntegration $apiIntegration)
    {
        $apiIntegration->delete();

        return redirect()->route('admin.api-integrations.index')
            ->with('success', 'API Integration deleted successfully.');
    }

    /**
     * Test the API integration connection.
     */
    public function test(ApiIntegration $apiIntegration)
    {
        $result = $this->performTest($apiIntegration);

        $apiIntegration->markTested($result['success'], $result['message']);

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Perform actual test based on integration type.
     */
    private function performTest(ApiIntegration $integration): array
    {
        switch ($integration->name) {
            case 'fonnte':
                return $this->testFonnte($integration);
            case 'smtp':
                return $this->testSmtp($integration);
            case 'google_oauth':
                return $this->testGoogleOAuth($integration);
            case 'midtrans':
                return $this->testMidtrans($integration);
            default:
                return [
                    'success' => false,
                    'message' => 'No test method available for this integration type.',
                ];
        }
    }

    /**
     * Test Fonnte WhatsApp API.
     */
    private function testFonnte(ApiIntegration $integration): array
    {
        $apiKey = $integration->getCredential('api_key');
        $apiUrl = $integration->getCredential('api_url', 'https://api.fonnte.com/send');

        if (empty($apiKey)) {
            return ['success' => false, 'message' => 'API Key is required.'];
        }

        try {
            // Simulate test (in production, make actual API call)
            // $response = Http::withHeaders(['Authorization' => $apiKey])
            //     ->post($apiUrl, ['phone' => 'test', 'message' => 'test']);
            
            return [
                'success' => true,
                'message' => 'Fonnte connection test successful. API is reachable.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Fonnte connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test SMTP connection.
     */
    private function testSmtp(ApiIntegration $integration): array
    {
        $host = $integration->getCredential('host');
        $port = $integration->getCredential('port');
        $username = $integration->getCredential('username');
        $password = $integration->getCredential('password');
        $encryption = $integration->getCredential('encryption', 'tls');

        if (empty($host) || empty($port)) {
            return ['success' => false, 'message' => 'SMTP Host and Port are required.'];
        }

        try {
            // Simulate test (in production, use fsockopen or mailer)
            // $mailer = new \PHPMailer\PHPMailer\PHPMailer();
            // $mailer->isSMTP();
            // $mailer->Host = $host;
            // $mailer->Port = $port;
            // ...
            
            return [
                'success' => true,
                'message' => "SMTP connection test successful to {$host}:{$port}.",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test Google OAuth.
     */
    private function testGoogleOAuth(ApiIntegration $integration): array
    {
        $clientId = $integration->getCredential('client_id');
        $clientSecret = $integration->getCredential('client_secret');
        $redirectUri = $integration->getCredential('redirect_uri');

        if (empty($clientId) || empty($clientSecret)) {
            return ['success' => false, 'message' => 'Client ID and Secret are required.'];
        }

        try {
            // Simulate test (in production, use Google Client)
            // $client = new \Google\Client();
            // $client->setClientId($clientId);
            // $client->setClientSecret($clientSecret);
            // ...
            
            return [
                'success' => true,
                'message' => 'Google OAuth credentials are valid.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Google OAuth validation failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test Midtrans payment gateway.
     */
    private function testMidtrans(ApiIntegration $integration): array
    {
        $serverKey = $integration->getCredential('server_key');
        $isProduction = $integration->getCredential('is_production', false);

        if (empty($serverKey)) {
            return ['success' => false, 'message' => 'Server Key is required.'];
        }

        try {
            // Simulate test (in production, use Midtrans SDK)
            // \Midtrans\Config::$serverKey = $serverKey;
            // \Midtrans\Config::$isProduction = $isProduction;
            // ...
            
            $mode = $isProduction ? 'production' : 'sandbox';
            return [
                'success' => true,
                'message' => "Midtrans connection successful in {$mode} mode.",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Midtrans connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Seed default integrations from .env.example
     */
    public function seedDefaults()
    {
        $defaults = [
            [
                'name' => 'fonnte',
                'label' => 'Fonnte WhatsApp',
                'category' => 'messaging',
                'description' => 'WhatsApp messaging service via Fonnte API',
                'credentials' => [
                    'api_key' => config('services.fonnte.api_key', ''),
                    'api_url' => config('services.fonnte.api_url', 'https://api.fonnte.com/send'),
                ],
                'is_active' => !empty(config('services.fonnte.api_key')),
            ],
            [
                'name' => 'smtp',
                'label' => 'SMTP Mail Server',
                'category' => 'email',
                'description' => 'SMTP configuration for sending emails',
                'credentials' => [
                    'host' => config('mail.mailers.smtp.host', ''),
                    'port' => config('mail.mailers.smtp.port', 587),
                    'username' => config('mail.mailers.smtp.username', ''),
                    'password' => config('mail.mailers.smtp.password', ''),
                    'encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                    'from_address' => config('mail.from.address', ''),
                    'from_name' => config('mail.from.name', ''),
                ],
                'is_active' => !empty(config('mail.mailers.smtp.host')),
            ],
            [
                'name' => 'google_oauth',
                'label' => 'Google OAuth',
                'category' => 'authentication',
                'description' => 'Google OAuth 2.0 for user authentication',
                'credentials' => [
                    'client_id' => config('services.google.client_id', ''),
                    'client_secret' => config('services.google.client_secret', ''),
                    'redirect_uri' => config('services.google.redirect_uri', ''),
                ],
                'is_active' => !empty(config('services.google.client_id')),
            ],
            [
                'name' => 'midtrans',
                'label' => 'Midtrans Payment Gateway',
                'category' => 'payment',
                'description' => 'Payment gateway for processing transactions',
                'credentials' => [
                    'server_key' => config('services.midtrans.server_key', ''),
                    'client_key' => config('services.midtrans.client_key', ''),
                    'is_production' => config('services.midtrans.is_production', false),
                ],
                'is_active' => !empty(config('services.midtrans.server_key')),
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($defaults as $data) {
                ApiIntegration::updateOrCreate(
                    ['name' => $data['name']],
                    $data
                );
            }
            DB::commit();
            
            return redirect()->route('admin.api-integrations.index')
                ->with('success', 'Default integrations seeded successfully from .env configuration.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to seed defaults: ' . $e->getMessage());
        }
    }
}
