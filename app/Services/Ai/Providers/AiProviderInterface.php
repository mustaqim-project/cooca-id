<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

interface AiProviderInterface
{
    /**
     * @param array $payload
     * @return array{body: array, usage: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}}
     */
    public function chatCompletion(array $payload): array;
}
