<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\AiTokenLot;
use App\Models\AiTokenTransaction;
use App\Models\AiUsageLog;
use App\Models\AiWallet;
use App\Models\Customer;
use App\Models\License;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class AiTokenConsumptionService
{
    /**
     * Check if customer has enough available tokens.
     */
    public function checkAvailable(Customer $customer, int $requiredTokens = 1): array
    {
        $available = (int) AiTokenLot::where('customer_id', $customer->getKey())
            ->where('status', AiTokenLot::STATUS_ACTIVE)
            ->where('remaining_tokens', '>', 0)
            ->where('expires_at', '>', now())
            ->sum('remaining_tokens');

        $hasEnough = $available >= $requiredTokens;

        return [
            'allowed'          => $hasEnough,
            'available_tokens' => $available,
            'required_tokens'  => $requiredTokens,
            'shortage'         => max(0, $requiredTokens - $available),
        ];
    }

    /**
     * Consume tokens using FEFO (First Expired, First Out) with concurrency row-locking.
     *
     * @param Customer $customer
     * @param int $totalTokensToConsume
     * @param array $details [provider, model, input_tokens, output_tokens, cached_tokens, duration_ms, license_id, api_key_id, request_id, user_identifier]
     * @return array
     */
    public function consumeTokens(Customer $customer, int $totalTokensToConsume, array $details = []): array
    {
        if ($totalTokensToConsume <= 0) {
            return [
                'success'           => true,
                'consumed_tokens'   => 0,
                'remaining_balance' => $customer->getAvailableAiTokens(),
                'lots_used'         => [],
            ];
        }

        return DB::transaction(function () use ($customer, $totalTokensToConsume, $details) {
            $wallet = $customer->getOrCreateAiWallet();

            // 1. Lock Wallet row
            $wallet = AiWallet::where('id', $wallet->id)->lockForUpdate()->first();

            // 2. Fetch and LOCK all active candidate lots ordered by FEFO (expires_at ASC)
            $activeLots = AiTokenLot::where('customer_id', $customer->getKey())
                ->where('status', AiTokenLot::STATUS_ACTIVE)
                ->where('remaining_tokens', '>', 0)
                ->where('expires_at', '>', now())
                ->orderBy('expires_at', 'asc')
                ->lockForUpdate()
                ->get();

            $totalAvailable = (int) $activeLots->sum('remaining_tokens');

            if ($totalAvailable < $totalTokensToConsume) {
                Log::warning("[AiTokenConsumptionService] Insufficient tokens for customer #{$customer->id}. Available: {$totalAvailable}, Required: {$totalTokensToConsume}");
                throw new \RuntimeException("Saldo AI Token tidak mencukupi. Tersedia: " . number_format($totalAvailable) . " Token, Dibutuhkan: " . number_format($totalTokensToConsume) . " Token.");
            }

            $needed = $totalTokensToConsume;
            $balanceBefore = (int) $wallet->total_balance;
            $lotsUsed = [];
            $primaryLotId = null;

            // 3. Consume from lots using FEFO
            foreach ($activeLots as $lot) {
                if ($needed <= 0) {
                    break;
                }

                $availableInLot = (int) $lot->remaining_tokens;
                $deduct = min($needed, $availableInLot);

                $lot->remaining_tokens -= $deduct;
                $lot->used_tokens      += $deduct;

                if ($lot->remaining_tokens <= 0) {
                    $lot->status = AiTokenLot::STATUS_DEPLETED;
                }

                $lot->save();

                $lotsUsed[] = [
                    'lot_id'     => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'name'       => $lot->name,
                    'expires_at' => $lot->expires_at->toIso8601String(),
                    'deducted'   => $deduct,
                ];

                if (!$primaryLotId) {
                    $primaryLotId = $lot->id;
                }

                $needed -= $deduct;
            }

            $balanceAfter = max(0, $balanceBefore - $totalTokensToConsume);

            // 4. Create Transaction Ledger Record
            $tx = AiTokenTransaction::create([
                'wallet_id'       => $wallet->id,
                'customer_id'     => $customer->getKey(),
                'lot_id'          => $primaryLotId,
                'type'            => AiTokenTransaction::TYPE_USAGE,
                'tokens'          => -$totalTokensToConsume,
                'balance_before'  => $balanceBefore,
                'balance_after'   => $balanceAfter,
                'reference_type'  => 'ai_request',
                'reference_id'    => $details['request_id'] ?? null,
                'description'     => "Penggunaan model " . ($details['model'] ?? 'AI') . " (-{$totalTokensToConsume} Token)",
                'created_by'      => $details['user_identifier'] ?? 'api',
            ]);

            // 5. Create Detailed Granular AiUsageLog
            $usageLog = AiUsageLog::create([
                'customer_id'       => $customer->getKey(),
                'ai_api_key_id'     => $details['api_key_id'] ?? null,
                'license_id'        => $details['license_id'] ?? null,
                'token_lot_id'      => $primaryLotId,
                'provider'          => $details['provider'] ?? 'cooca',
                'model'             => $details['model'] ?? 'gpt-4o-mini',
                'input_tokens'      => (int) ($details['input_tokens'] ?? ($details['prompt_tokens'] ?? 0)),
                'output_tokens'     => (int) ($details['output_tokens'] ?? ($details['completion_tokens'] ?? 0)),
                'cached_tokens'     => (int) ($details['cached_tokens'] ?? 0),
                'prompt_tokens'     => (int) ($details['prompt_tokens'] ?? ($details['input_tokens'] ?? 0)),
                'completion_tokens' => (int) ($details['completion_tokens'] ?? ($details['output_tokens'] ?? 0)),
                'total_tokens'      => $totalTokensToConsume,
                'cost_usd'          => $details['cost_usd'] ?? null,
                'estimated_cost'    => $details['estimated_cost'] ?? null,
                'actual_cost'       => $details['actual_cost'] ?? null,
                'request_id'        => $details['request_id'] ?? null,
                'user_identifier'   => $details['user_identifier'] ?? null,
                'status'            => 'success',
                'http_status'       => 200,
                'duration_ms'       => $details['duration_ms'] ?? 0,
            ]);

            // 6. Update Wallet Summary Totals
            $wallet->update([
                'total_balance' => $balanceAfter,
                'total_used'    => $wallet->total_used + $totalTokensToConsume,
            ]);

            Log::info("[AiTokenConsumptionService] Successfully consumed {$totalTokensToConsume} tokens for customer #{$customer->id}. New balance: {$balanceAfter}");

            return [
                'success'           => true,
                'consumed_tokens'   => $totalTokensToConsume,
                'remaining_balance' => $balanceAfter,
                'transaction_id'    => $tx->id,
                'usage_log_id'      => $usageLog->id,
                'lots_used'         => $lotsUsed,
            ];
        });
    }
}
