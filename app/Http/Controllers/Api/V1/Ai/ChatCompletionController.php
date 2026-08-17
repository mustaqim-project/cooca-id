<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class ChatCompletionController extends Controller
{
    public function __construct(private readonly AiGatewayService $gateway) {}

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
}
