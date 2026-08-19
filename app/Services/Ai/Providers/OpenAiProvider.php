<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final class OpenAiProvider implements AiProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl
    ) {}

    public function chatCompletion(array $payload): array
    {
        $url = rtrim(trim($this->baseUrl), '/');
        if (!str_ends_with($url, '/chat/completions')) {
            $url = "{$url}/chat/completions";
        }

        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $request = Http::withHeaders($headers)->timeout(45);

        if (!empty($this->apiKey)) {
            $request = $request->withToken(trim($this->apiKey));
        }

        $response = $request->post($url, $payload);

        if ($response->failed()) {
            $errorJson = $response->json();
            $errorMessage = $errorJson['error']['message'] 
                ?? $errorJson['message'] 
                ?? $errorJson['detail']
                ?? $response->body();

            throw new RuntimeException("AI Endpoint Error ({$response->status()}): {$errorMessage}");
        }

        $body = $response->json();

        return [
            'body' => $body,
            'usage' => [
                'prompt_tokens'     => $body['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $body['usage']['completion_tokens'] ?? 0,
                'total_tokens'      => $body['usage']['total_tokens'] ?? 0,
            ],
        ];
    }
}
