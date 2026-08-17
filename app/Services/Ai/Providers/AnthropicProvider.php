<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

final class AnthropicProvider implements AiProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl
    ) {}

    public function chatCompletion(array $payload): array
    {
        // Translate OpenAI payload to Anthropic format
        $anthropicPayload = [
            'model' => $payload['model'],
            'messages' => $payload['messages'],
            'max_tokens' => $payload['max_tokens'] ?? 4096,
        ];
        
        if (isset($payload['temperature'])) {
            $anthropicPayload['temperature'] = $payload['temperature'];
        }

        $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout(45)
            ->post("{$this->baseUrl}/v1/messages", $anthropicPayload);

        $response->throw();

        $body = $response->json();

        // Translate response back to OpenAI-compatible format
        $openAiBody = [
            'id' => $body['id'] ?? uniqid('msg_'),
            'object' => 'chat.completion',
            'created' => time(),
            'model' => $payload['model'],
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => Arr::get($body, 'content.0.text', ''),
                    ],
                    'finish_reason' => $body['stop_reason'] === 'end_turn' ? 'stop' : $body['stop_reason'],
                ]
            ],
            'usage' => [
                'prompt_tokens' => $body['usage']['input_tokens'] ?? 0,
                'completion_tokens' => $body['usage']['output_tokens'] ?? 0,
                'total_tokens' => ($body['usage']['input_tokens'] ?? 0) + ($body['usage']['output_tokens'] ?? 0),
            ],
        ];

        return [
            'body' => $openAiBody,
            'usage' => $openAiBody['usage'],
        ];
    }
}
