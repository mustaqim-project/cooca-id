<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use App\Models\AiApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class AiApiKeyAuthTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;
    private License $license;
    private string $rawKey;
    private AiApiKey $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $product = Product::create([
            'name' => 'AI Module',
            'slug' => 'ai-module',
            'code' => 'ai-assistant',
            'description' => 'Test AI Module',
            'base_price' => 0,
            'is_active' => true,
        ]);

        $plan = \App\Models\SubscriptionPlan::create([
            'product_id' => $product->id,
            'name' => 'Monthly',
            'interval' => 'month',
            'duration_months' => 1,
            'price' => 10,
            'is_active' => true,
        ]);

        $this->license = License::create([
            'id' => Str::uuid()->toString(),
            'customer_id' => $this->customer->id,
            'product_id' => $product->id,
            'subscription_plan_id' => $plan->id,
            'license_key' => 'TEST-LICENSE-123',
            'license_code' => 'LC-123',
            'token_code' => 'TC-123',
            'domain' => 'example.com',
            'status' => 'active',
            'expires_at' => now()->addYear(),
        ]);

        $this->rawKey = 'sk-' . Str::random(32);
        
        $this->apiKey = \App\Models\AiApiKey::create([
            'license_id' => $this->license->id,
            'customer_id' => $this->customer->id,
            'name' => 'Test API Key',
            'key_prefix' => substr($this->rawKey, 0, 12),
            'key_hash' => hash('sha256', $this->rawKey),
            'status' => 'active'
        ]);
    }

    public function test_rejects_missing_bearer_token()
    {
        $response = $this->postJson('/api/v1/ai/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [['role' => 'user', 'content' => 'Hello']]
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => [
                         'message' => 'Missing API key'
                     ]
                 ]);
    }

    public function test_rejects_invalid_api_key()
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-key')
                         ->postJson('/api/v1/ai/chat/completions', [
                             'model' => 'gpt-4',
                             'messages' => [['role' => 'user', 'content' => 'Hello']]
                         ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => [
                         'message' => 'Invalid API key'
                     ]
                 ]);
    }

    public function test_rejects_revoked_api_key()
    {
        $this->apiKey->update(['status' => 'revoked']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->rawKey)
                         ->postJson('/api/v1/ai/chat/completions', [
                             'model' => 'gpt-4',
                             'messages' => [['role' => 'user', 'content' => 'Hello']]
                         ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => [
                         'message' => 'Invalid API key'
                     ]
                 ]);
    }

    public function test_rejects_inactive_license()
    {
        $this->license->update(['status' => 'suspended']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->rawKey)
                         ->postJson('/api/v1/ai/chat/completions', [
                             'model' => 'gpt-4',
                             'messages' => [['role' => 'user', 'content' => 'Hello']]
                         ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'error' => [
                         'message' => 'AI module license is not active'
                     ]
                 ]);
    }

    public function test_rejects_expired_license()
    {
        $this->license->update(['expires_at' => now()->subDay()]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->rawKey)
                         ->postJson('/api/v1/ai/chat/completions', [
                             'model' => 'gpt-4',
                             'messages' => [['role' => 'user', 'content' => 'Hello']]
                         ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'error' => [
                         'message' => 'AI module license is not active'
                     ]
                 ]);
    }
}
