<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final class GeminiProvider implements AiProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta',
    ) {}

    public function chatCompletion(array $payload): array
    {
        $model = $payload['model'];
        $url = rtrim($this->baseUrl, '/') . "/models/{$model}:generateContent?key=" . $this->apiKey;

        $geminiPayload = $this->transformToGeminiFormat($payload);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->timeout(60)
        ->post($url, $geminiPayload);

        if (!$response->successful()) {
            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? 'Google Gemini API request failed: ' . $response->status();
            throw new RuntimeException($errorMessage);
        }

        $resJson = $response->json();

        return $this->transformToOpenAiFormat($resJson, $model);
    }

    private function transformToGeminiFormat(array $payload): array
    {
        $contents = [];
        $systemInstruction = null;

        foreach ($payload['messages'] as $msg) {
            $role = $msg['role'];
            $content = $msg['content'] ?? '';

            if ($role === 'system') {
                $systemInstruction = [
                    'parts' => [['text' => $content]],
                ];
            } else {
                $contents[] = [
                    'role' => $role === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $content]],
                ];
            }
        }

        $geminiData = [
            'contents' => $contents,
        ];

        if ($systemInstruction) {
            $geminiData['systemInstruction'] = $systemInstruction;
        }

        $genConfig = [];
        if (isset($payload['temperature'])) {
            $genConfig['temperature'] = (float) $payload['temperature'];
        }
        if (isset($payload['max_tokens'])) {
            $genConfig['maxOutputTokens'] = (int) $payload['max_tokens'];
        }

        if (!empty($genConfig)) {
            $geminiData['generationConfig'] = $genConfig;
        }

        return $geminiData;
    }

    private function transformToOpenAiFormat(array $resJson, string $model): array
    {
        $text = $resJson['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $finishReason = $resJson['candidates'][0]['finishReason'] ?? 'stop';
        
        $usageMetadata = $resJson['usageMetadata'] ?? [];
        $promptTokens = $usageMetadata['promptTokenCount'] ?? 0;
        $completionTokens = $usageMetadata['candidatesTokenCount'] ?? 0;
        $totalTokens = $usageMetadata['totalTokenCount'] ?? ($promptTokens + $completionTokens);

        $body = [
            'id' => 'chatcmpl-gemini-' . uniqid(),
            'object' => 'chat.completion',
            'created' => time(),
            'model' => $model,
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => $text,
                    ],
                    'finish_reason' => strtolower($finishReason),
                ],
            ],
            'usage' => [
                'prompt_tokens' => $promptTokens,
                'completion_tokens' => $completionTokens,
                'total_tokens' => $totalTokens,
            ],
        ];

        return [
            'body' => $body,
            'usage' => $body['usage'],
        ];
    }
}
