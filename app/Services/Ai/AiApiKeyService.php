<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\License;
use Illuminate\Support\Str;

final class AiApiKeyService
{
    private const PREFIX_LENGTH = 12;

    /**
     * @return array{model: AiApiKey, plain_key: string} plain_key HANYA muncul sekali di sini,
     *         tidak pernah bisa diambil ulang setelah response ini dikirim ke customer.
     */
    public function issueForLicense(License $license, string $name, ?string $domainId = null): array
    {
        $rawKey = 'cooca_ai_live_' . Str::random(40);
        $prefix = Str::substr($rawKey, 0, self::PREFIX_LENGTH);

        $model = AiApiKey::create([
            'license_id'  => $license->id,
            'customer_id' => $license->customer_id,
            'domain_id'   => $domainId,
            'name'        => $name,
            'key_prefix'  => $prefix,
            'key_hash'    => hash('sha256', $rawKey),
            'status'      => 'active',
        ]);

        return ['model' => $model, 'plain_key' => $rawKey];
    }

    public function revoke(AiApiKey $key): void
    {
        $key->update(['status' => 'revoked', 'revoked_at' => now()]);
    }
}
