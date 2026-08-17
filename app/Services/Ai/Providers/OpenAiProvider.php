<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;

final class OpenAiProvider implements AiProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl
    ) {}

    public function chatCompletion(array $payload): array
    {
        $response = Http::withToken($this->apiKey)
            ->timeout(45) // limit timeout to 45s for shared hosting compatibility
            ->post("{$this->baseUrl}/chat/completions", $payload);

        $response->throw();

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
