<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiTokenLot;
use App\Models\AiTokenTransaction;
use App\Models\AiWallet;
use App\Models\Customer;
use App\Models\License;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class AiTokenWalletService
{
    /**
     * Credit a new Token Lot to the customer's wallet (e.g. from Top Up, Subscription, Bonus, Promo).
     *
     * @param Customer $customer
     * @param int $tokens
     * @param string $sourceType (topup, subscription, bonus, promotion, refund, admin_adjustment)
     * @param string|null $sourceId (e.g. transaction_id, subscription_id)
     * @param string $name
     * @param License|null $license
     * @param Carbon|null $startsAt
     * @param Carbon|null $expiresAt (Defaults to 30 days from purchase for topups!)
     * @param string|null $idempotencyKey
     * @param array $metadata
     * @return AiTokenLot
     */
    public function creditTokenLot(
        Customer $customer,
        int $tokens,
        string $sourceType = AiTokenLot::SOURCE_TOPUP,
        ?string $sourceId = null,
        string $name = 'AI Token Top Up',
        ?License $license = null,
        ?Carbon $startsAt = null,
        ?Carbon $expiresAt = null,
        ?string $idempotencyKey = null,
        array $metadata = []
    ): AiTokenLot {
        if ($tokens <= 0) {
            throw new \InvalidArgumentException('Token amount must be greater than zero.');
        }

        // 1. Idempotency Check: ensure transaction or token creation isn't duplicated
        if ($idempotencyKey) {
            $existingTx = AiTokenTransaction::where('idempotency_key', $idempotencyKey)->first();
            if ($existingTx && $existingTx->lot_id) {
                $existingLot = AiTokenLot::find($existingTx->lot_id);
                if ($existingLot) {
                    Log::info("[AiTokenWalletService] Idempotency match found for key '{$idempotencyKey}'. Skipping duplicate credit.");
                    return $existingLot;
                }
            }
        }

        return DB::transaction(function () use (
            $customer,
            $tokens,
            $sourceType,
            $sourceId,
            $name,
            $license,
            $startsAt,
            $expiresAt,
            $idempotencyKey,
            $metadata
        ) {
            $wallet = $customer->getOrCreateAiWallet();

            // Lock wallet row for update
            $wallet = AiWallet::where('id', $wallet->id)->lockForUpdate()->first();

            $now = now();
            $purchaseDate = $now->copy();
            $effectiveStartsAt = $startsAt ? $startsAt->copy() : $now->copy();

            // Rule: Every Top-Up AI Token is valid for 30 DAYS from purchase date!
            if (!$expiresAt) {
                if ($sourceType === AiTokenLot::SOURCE_TOPUP) {
                    $effectiveExpiresAt = $purchaseDate->copy()->addDays(30);
                } else {
                    $effectiveExpiresAt = $effectiveStartsAt->copy()->addDays(30);
                }
            } else {
                $effectiveExpiresAt = $expiresAt->copy();
            }

            $lotNumber = 'LOT-' . $now->format('Ymd') . '-' . strtoupper(Str::random(6));

            $lot = AiTokenLot::create([
                'wallet_id'        => $wallet->id,
                'customer_id'      => $customer->getKey(),
                'license_id'       => $license?->id,
                'lot_number'       => $lotNumber,
                'name'             => $name,
                'source_type'      => $sourceType,
                'source_id'        => $sourceId,
                'original_tokens'  => $tokens,
                'remaining_tokens' => $tokens,
                'used_tokens'      => 0,
                'purchased_at'     => $purchaseDate,
                'starts_at'        => $effectiveStartsAt,
                'expires_at'       => $effectiveExpiresAt,
                'status'           => AiTokenLot::STATUS_ACTIVE,
                'metadata'         => $metadata,
            ]);

            $balanceBefore = (int) $wallet->total_balance;
            $balanceAfter  = $balanceBefore + $tokens;

            // Record transaction ledger
            $txType = match ($sourceType) {
                AiTokenLot::SOURCE_SUBSCRIPTION => AiTokenTransaction::TYPE_SUBSCRIPTION,
                AiTokenLot::SOURCE_BONUS        => AiTokenTransaction::TYPE_BONUS,
                AiTokenLot::SOURCE_PROMOTION    => AiTokenTransaction::TYPE_PROMOTION,
                AiTokenLot::SOURCE_REFUND       => AiTokenTransaction::TYPE_REFUND,
                default                         => AiTokenTransaction::TYPE_PURCHASE,
            };

            AiTokenTransaction::create([
                'wallet_id'       => $wallet->id,
                'customer_id'     => $customer->getKey(),
                'lot_id'          => $lot->id,
                'type'            => $txType,
                'tokens'          => $tokens,
                'balance_before'  => $balanceBefore,
                'balance_after'   => $balanceAfter,
                'reference_type'  => $sourceType,
                'reference_id'    => $sourceId,
                'idempotency_key' => $idempotencyKey,
                'description'     => "Penambahan kuota AI Token (+{$tokens} Token) via {$name} [Lot: {$lotNumber}]",
                'created_by'      => 'system',
            ]);

            // Update Wallet balance & purchased total
            $wallet->update([
                'total_balance'   => $balanceAfter,
                'total_purchased' => $wallet->total_purchased + $tokens,
            ]);

            Log::info("[AiTokenWalletService] Credited {$tokens} tokens to customer #{$customer->id} [Lot: {$lotNumber}]. New balance: {$balanceAfter}");

            return $lot;
        });
    }

    /**
     * Get complete Wallet summary & breakdown for a customer.
     */
    public function getWalletSummary(Customer $customer): array
    {
        $wallet = $customer->getOrCreateAiWallet();

        // 1. Calculate active available tokens
        $activeLots = AiTokenLot::where('customer_id', $customer->getKey())
            ->where('status', AiTokenLot::STATUS_ACTIVE)
            ->where('remaining_tokens', '>', 0)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->get();

        $totalAvailable = (int) $activeLots->sum('remaining_tokens');

        // 2. Token Breakdown by Source
        $subscriptionTokens = (int) $activeLots->where('source_type', AiTokenLot::SOURCE_SUBSCRIPTION)->sum('remaining_tokens');
        $topupTokens        = (int) $activeLots->where('source_type', AiTokenLot::SOURCE_TOPUP)->sum('remaining_tokens');
        $bonusTokens        = (int) $activeLots->whereIn('source_type', [AiTokenLot::SOURCE_BONUS, AiTokenLot::SOURCE_PROMOTION])->sum('remaining_tokens');

        // 3. Usage this month
        $usedThisMonth = (int) \App\Models\AiUsageLog::where('customer_id', $customer->getKey())
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_tokens');

        // 4. Expiring Soon (Tokens expiring within the next 7 days)
        $sevenDaysAhead = now()->addDays(7);
        $expiringLots = $activeLots->filter(fn($lot) => $lot->expires_at <= $sevenDaysAhead);
        $expiringSoonTokens = (int) $expiringLots->sum('remaining_tokens');

        // 5. Next Expiration
        $nextExpiringLot = $activeLots->first(); // Since already ordered by expires_at ASC
        $nextExpirationDate = $nextExpiringLot ? $nextExpiringLot->expires_at : null;

        // 6. Expiration Warnings (H-7, H-3, H-1)
        $warnings = [];
        foreach ($activeLots as $lot) {
            $daysLeft = $lot->daysUntilExpiration();
            if ($daysLeft <= 1) {
                $warnings[] = [
                    'severity' => 'danger',
                    'message'  => number_format($lot->remaining_tokens) . " Token AI pada [{$lot->name}] akan kedaluwarsa dalam waktu kurang dari 24 jam (" . $lot->expires_at->translatedFormat('d M Y, H:i') . ").",
                    'lot'      => $lot,
                ];
            } elseif ($daysLeft <= 3) {
                $warnings[] = [
                    'severity' => 'warning',
                    'message'  => number_format($lot->remaining_tokens) . " Token AI pada [{$lot->name}] akan kedaluwarsa dalam {$daysLeft} hari (" . $lot->expires_at->translatedFormat('d M Y') . ").",
                    'lot'      => $lot,
                ];
            } elseif ($daysLeft <= 7) {
                $warnings[] = [
                    'severity' => 'info',
                    'message'  => number_format($lot->remaining_tokens) . " Token AI pada [{$lot->name}] akan kedaluwarsa dalam {$daysLeft} hari (" . $lot->expires_at->translatedFormat('d M Y') . ").",
                    'lot'      => $lot,
                ];
            }
        }

        return [
            'wallet'               => $wallet,
            'total_available'      => $totalAvailable,
            'used_this_month'      => $usedThisMonth,
            'expiring_soon'        => $expiringSoonTokens,
            'next_expiration_date' => $nextExpirationDate,
            'breakdown'            => [
                'subscription' => $subscriptionTokens,
                'topup'        => $topupTokens,
                'bonus'        => $bonusTokens,
            ],
            'active_lots'          => $activeLots,
            'warnings'             => $warnings,
        ];
    }
}
