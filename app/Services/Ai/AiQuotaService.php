<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiPlanConfig;
use App\Models\AiUsageCycle;
use App\Models\License;

final class AiQuotaService
{
    public function planConfigFor(License $license): AiPlanConfig
    {
        // Cari config berdasar plan. Kalau tidak ada, anggap ini bukan plan AI
        $config = AiPlanConfig::where('subscription_plan_id', $license->subscription_plan_id)->first();
        if (!$config) {
            throw new \RuntimeException("Plan configuration not found for subscription_plan_id: {$license->subscription_plan_id}");
        }
        return $config;
    }

    public function currentCycleFor(License $license): AiUsageCycle
    {
        $cycle = AiUsageCycle::where('license_id', $license->id)
            ->where('cycle_start', '<=', now())
            ->where('cycle_end', '>=', now())
            ->first();

        if (!$cycle) {
            return $this->startNewCycle($license);
        }

        return $cycle;
    }

    public function startNewCycle(License $license): AiUsageCycle
    {
        $planConfig = $this->planConfigFor($license);

        $now = now();
        $cycleStart = $now->copy()->startOfDay();
        // Misalkan siklus bulanan, mengikuti konvensi typical (30 hari atau tgl ke tgl bulan depan)
        $cycleEnd = $now->copy()->addMonth()->startOfDay();

        return AiUsageCycle::firstOrCreate([
            'license_id' => $license->id,
            'cycle_start' => $cycleStart,
        ], [
            'cycle_end' => $cycleEnd,
            'tokens_used' => 0,
            'token_quota' => $planConfig->monthly_token_quota,
        ]);
    }

    public function isExhausted(AiUsageCycle $cycle): bool
    {
        return $cycle->tokens_used >= $cycle->token_quota;
    }

    public function increment(AiUsageCycle $cycle, int $tokens): AiUsageCycle
    {
        $cycle->increment('tokens_used', $tokens);
        return $cycle->fresh();
    }
}
