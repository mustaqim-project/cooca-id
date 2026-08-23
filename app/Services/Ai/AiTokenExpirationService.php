<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiTokenLot;
use App\Models\AiTokenTransaction;
use App\Models\AiWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class AiTokenExpirationService
{
    /**
     * Process expired token lots across all customers.
     *
     * @return array [processed_lots => int, expired_tokens => int]
     */
    public function processExpirations(): array
    {
        $now = now();

        $expiredLots = AiTokenLot::where('status', AiTokenLot::STATUS_ACTIVE)
            ->where('expires_at', '<', $now)
            ->get();

        $processedCount = 0;
        $totalExpiredTokens = 0;

        foreach ($expiredLots as $lot) {
            DB::transaction(function () use ($lot, &$processedCount, &$totalExpiredTokens) {
                // Lock lot & wallet
                $lot = AiTokenLot::where('id', $lot->id)->lockForUpdate()->first();
                if (!$lot || $lot->status !== AiTokenLot::STATUS_ACTIVE || $lot->expires_at->isFuture()) {
                    return;
                }

                $wallet = AiWallet::where('id', $lot->wallet_id)->lockForUpdate()->first();
                $remainingBefore = (int) $lot->remaining_tokens;

                if ($wallet && $remainingBefore > 0) {
                    $balanceBefore = (int) $wallet->total_balance;
                    $balanceAfter  = max(0, $balanceBefore - $remainingBefore);

                    // Create expiration audit transaction
                    AiTokenTransaction::create([
                        'wallet_id'       => $wallet->id,
                        'customer_id'     => $lot->customer_id,
                        'lot_id'          => $lot->id,
                        'type'            => AiTokenTransaction::TYPE_EXPIRATION,
                        'tokens'          => -$remainingBefore,
                        'balance_before'  => $balanceBefore,
                        'balance_after'   => $balanceAfter,
                        'reference_type'  => 'cron_expiration',
                        'reference_id'    => $lot->id,
                        'description'     => "Masa berlaku token telah kedaluwarsa (-{$remainingBefore} Token) pada [Lot: {$lot->lot_number}]",
                        'created_by'      => 'scheduler',
                    ]);

                    // Update wallet totals
                    $wallet->update([
                        'total_balance' => $balanceAfter,
                        'total_expired' => $wallet->total_expired + $remainingBefore,
                    ]);

                    $totalExpiredTokens += $remainingBefore;
                }

                // Update lot status
                $lot->update([
                    'remaining_tokens' => 0,
                    'status'           => AiTokenLot::STATUS_EXPIRED,
                ]);

                $processedCount++;

                Log::info("[AiTokenExpirationService] Expired Lot #{$lot->lot_number} for Customer #{$lot->customer_id}. Forfeited tokens: {$remainingBefore}");
            });
        }

        return [
            'processed_lots' => $processedCount,
            'expired_tokens' => $totalExpiredTokens,
        ];
    }
}
