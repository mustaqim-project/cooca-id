<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiProviderConfig;
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
        @ini_set('max_execution_time', '600');
        @set_time_limit(600);

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
            ->header('X-Cooca-Model', (string) $validated['model'])
            ->header('X-Cooca-Tokens-Used', (string) ($result['tokens_used_this_cycle'] ?? 0))
            ->header('X-Cooca-Tokens-Remaining', (string) ($result['tokens_remaining'] ?? 0));
    }

    public function models(Request $request): JsonResponse
    {
        $license = $request->attributes->get('ai_license');

        $providerConfig = AiProviderConfig::first();
        $configuredModels = $providerConfig ? $providerConfig->getModelsList() : ['gpt-4o-mini', 'gpt-4o'];

        if ($license) {
            $planConfig = $this->quotaService->planConfigFor($license);
            $allowed = $planConfig->allowed_models ?? [];
            if (!empty($allowed)) {
                $intersect = array_values(array_intersect($configuredModels, $allowed));
                $configuredModels = !empty($intersect) ? $intersect : $allowed;
            }
        }

        $availableModels = [];
        foreach ($configuredModels as $modelKey) {
            $availableModels[] = [
                'id'             => $modelKey,
                'object'         => 'model',
                'created'        => 1700000000,
                'owned_by'       => 'cooca',
                'name'           => $modelKey,
                'provider'       => 'Cooca AI Gateway',
                'context_window' => '128k',
                'description'    => "Model {$modelKey} via Cooca AI Gateway",
                'permission'     => [],
                'root'           => $modelKey,
                'parent'         => null,
            ];
        }

        return response()->json([
            'object' => 'list',
            'data'   => $availableModels,
        ]);
    }

    public function quota(Request $request): JsonResponse
    {
        $license = $request->attributes->get('ai_license');
        $planConfig = $this->quotaService->planConfigFor($license);
        $cycle = $this->quotaService->currentCycleFor($license);

        return response()->json([
            'license_id'          => $license->id,
            'plan_id'             => $license->subscription_plan_id,
            'cycle_start'         => $cycle->cycle_start?->toIso8601String(),
            'cycle_end'           => $cycle->cycle_end?->toIso8601String(),
            'token_quota'         => $cycle->token_quota,
            'tokens_used'         => $cycle->tokens_used,
            'tokens_remaining'    => max(0, $cycle->token_quota - $cycle->tokens_used),
            'percent_used'        => $cycle->token_quota > 0 ? round(($cycle->tokens_used / $cycle->token_quota) * 100, 2) : 0,
            'overage_policy'      => $planConfig->overage_policy ?? 'hard_stop',
            'requests_per_minute' => $planConfig->requests_per_minute ?? 60,
            'allowed_models'      => $planConfig->allowed_models ?? [],
        ]);
    }
}
