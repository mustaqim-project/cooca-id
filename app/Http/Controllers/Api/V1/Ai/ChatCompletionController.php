<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiGatewayService;
use App\Services\Ai\AiQuotaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class ChatCompletionController extends Controller
{
    public function __construct(
        private readonly AiGatewayService $gateway,
        private readonly AiQuotaService $quotaService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model'       => 'required|string',
            'messages'    => 'required|array|min:1',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens'  => 'nullable|integer|min:1|max:8000',
        ]);

        $apiKey = $request->attributes->get('ai_api_key');
        $license = $request->attributes->get('ai_license');

        $result = $this->gateway->handleChatCompletion($apiKey, $license, $validated);

        return response()->json($result['payload'], $result['status'])
            ->header('X-Cooca-Tokens-Used', (string) $result['tokens_used_this_cycle'])
            ->header('X-Cooca-Tokens-Remaining', (string) $result['tokens_remaining']);
    }

    public function models(Request $request): JsonResponse
    {
        $license = $request->attributes->get('ai_license');
        $planConfig = $this->quotaService->planConfigFor($license);

        $modelDefinitions = [
            'gpt-4o' => ['name' => 'GPT-4o', 'provider' => 'OpenAI', 'context' => '128k', 'description' => 'Flagship multi-modal model for high-complexity tasks.'],
            'gpt-4o-mini' => ['name' => 'GPT-4o Mini', 'provider' => 'OpenAI', 'context' => '128k', 'description' => 'Fast, lightweight intelligence for everyday tasks.'],
            'gemini-1.5-flash' => ['name' => 'Gemini 1.5 Flash', 'provider' => 'Google', 'context' => '1M', 'description' => 'High speed, high throughput model for rapid workflows.'],
            'gemini-1.5-pro' => ['name' => 'Gemini 1.5 Pro', 'provider' => 'Google', 'context' => '2M', 'description' => 'Massive context model for deep reasoning and code generation.'],
            'gemini-2.0-flash' => ['name' => 'Gemini 2.0 Flash', 'provider' => 'Google', 'context' => '1M', 'description' => 'Next-generation multimodal reasoning and latency reduction.'],
            'claude-3-5-sonnet-20241022' => ['name' => 'Claude 3.5 Sonnet', 'provider' => 'Anthropic', 'context' => '200k', 'description' => 'State-of-the-art coding, analysis, and writing.'],
            'claude-3-5-haiku-20241022' => ['name' => 'Claude 3.5 Haiku', 'provider' => 'Anthropic', 'context' => '200k', 'description' => 'Ultra fast performance at a fraction of the cost.'],
            'deepseek-chat' => ['name' => 'DeepSeek-V3', 'provider' => 'DeepSeek', 'context' => '64k', 'description' => 'Powerful mixture-of-experts model for general reasoning.'],
            'deepseek-reasoner' => ['name' => 'DeepSeek-R1', 'provider' => 'DeepSeek', 'context' => '64k', 'description' => 'Advanced chain-of-thought reasoning and mathematics.'],
        ];

        $availableModels = [];
        $allowed = $planConfig->allowed_models ?? [];

        foreach ($allowed as $modelKey) {
            $def = $modelDefinitions[$modelKey] ?? [
                'name' => $modelKey,
                'provider' => 'AI Provider',
                'context' => '32k',
                'description' => 'General purpose AI model',
            ];

            $availableModels[] = [
                'id' => $modelKey,
                'object' => 'model',
                'created' => 1700000000,
                'owned_by' => strtolower($def['provider']),
                'name' => $def['name'],
                'provider' => $def['provider'],
                'context_window' => $def['context'],
                'description' => $def['description'],
            ];
        }

        return response()->json([
            'object' => 'list',
            'data' => $availableModels,
        ]);
    }

    public function quota(Request $request): JsonResponse
    {
        $license = $request->attributes->get('ai_license');
        $planConfig = $this->quotaService->planConfigFor($license);
        $cycle = $this->quotaService->currentCycleFor($license);

        return response()->json([
            'license_id' => $license->id,
            'plan_id' => $license->subscription_plan_id,
            'cycle_start' => $cycle->cycle_start?->toIso8601String(),
            'cycle_end' => $cycle->cycle_end?->toIso8601String(),
            'token_quota' => $cycle->token_quota,
            'tokens_used' => $cycle->tokens_used,
            'tokens_remaining' => max(0, $cycle->token_quota - $cycle->tokens_used),
            'percent_used' => $cycle->token_quota > 0 ? round(($cycle->tokens_used / $cycle->token_quota) * 100, 2) : 0,
            'overage_policy' => $planConfig->overage_policy ?? 'hard_stop',
            'requests_per_minute' => $planConfig->requests_per_minute ?? 60,
        ]);
    }
}
