<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use App\Models\AiApiKey;
use App\Models\AiUsageCycle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class AiGatewayQuotaEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;
    private License $license;
    private string $rawKey;
    private AiApiKey $apiKey;
    private AiUsageCycle $cycle;

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

        \App\Models\AiPlanConfig::create([
            'subscription_plan_id' => $plan->id,
            'monthly_token_quota' => 1000,
            'allowed_models' => ['gpt-3.5-turbo', 'gpt-4'],
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

        $this->cycle = AiUsageCycle::create([
            'license_id' => $this->license->id,
            'cycle_start' => now()->startOfMonth(),
            'cycle_end' => now()->endOfMonth(),
            'token_quota' => 1000,
            'tokens_used' => 0,
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

        \App\Models\AiProviderConfig::create([
            'provider' => 'openai',
            'api_key' => 'dummy-provider-key',
            'base_url' => 'https://api.openai.com/v1',
            'is_active' => true,
        ]);
    }

    public function test_allows_request_when_quota_available(): void
    {
        // Mock the provider response instead of hitting real OpenAI API
        \Illuminate\Support\Facades\Http::fake([
            'api.openai.com/v1/chat/completions' => \Illuminate\Support\Facades\Http::response([
                'id' => 'chatcmpl-123',
                'object' => 'chat.completion',
                'created' => time(),
                'model' => 'gpt-3.5-turbo-0125',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => ['role' => 'assistant', 'content' => 'Hello!'],
                        'logprobs' => null,
                        'finish_reason' => 'stop',
                    ]
                ],
                'usage' => [
                    'prompt_tokens' => 10,
                    'completion_tokens' => 5,
                    'total_tokens' => 15,
                ],
            ], 200)
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->rawKey
        ])->postJson('/api/v1/ai/chat/completions', [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'model' => 'gpt-3.5-turbo'
        ]);

        $response->assertStatus(200);
        
        // Assert quota was deducted
        $this->cycle->refresh();
        $this->assertEquals(15, $this->cycle->tokens_used);
        
        $this->assertDatabaseHas('ai_usage_logs', [
            'license_id' => $this->license->id,
            'model' => 'gpt-3.5-turbo',
            'total_tokens' => 15
        ]);
    }

    public function test_rejects_request_when_quota_exceeded(): void
    {
        // Max out the quota
        $this->cycle->update(['tokens_used' => 1000]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->rawKey
        ])->postJson('/api/v1/ai/chat/completions', [
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'model' => 'gpt-3.5-turbo'
        ]);

        $response->assertStatus(429)
                 ->assertJson([
                     'error' => [
                         'message' => 'Monthly AI token quota exceeded.',
                         'tokens_used' => 1000,
                         'tokens_quota' => 1000,
                     ]
                 ]);
        
        // Ensure no mock was called and quota didn't increase
        $this->cycle->refresh();
        $this->assertEquals(1000, $this->cycle->tokens_used);
    }
}
