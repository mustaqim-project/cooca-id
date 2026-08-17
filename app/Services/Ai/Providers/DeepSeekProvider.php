<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final class DeepSeekProvider implements AiProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://api.deepseek.com/v1',
    ) {}

    public function chatCompletion(array $payload): array
    {
        $url = rtrim($this->baseUrl, '/') . '/chat/completions';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])
        ->timeout(60)
        ->post($url, $payload);

        if (!$response->successful()) {
            $error = $response->json()['error']['message'] ?? 'DeepSeek API error: ' . $response->status();
            throw new RuntimeException($error);
        }

        $body = $response->json();

        return [
            'body'  => $body,
            'usage' => $body['usage'] ?? [
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
            ],
        ];
    }
}
