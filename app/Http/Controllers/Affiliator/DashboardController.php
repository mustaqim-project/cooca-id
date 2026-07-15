<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use Illuminate\Support\Facades\Auth;

/**
 * Affiliator Dashboard Controller
 *
 * Handles affiliator dashboard with referral, commission, and withdrawal statistics.
 */
final class DashboardController extends Controller
{
    /**
     * Display affiliator dashboard with comprehensive statistics.
     */
    public function index()
    {
        $affiliator = Auth::guard('affiliator')->user();

        // === Referral Statistics ===
        $totalReferrals       = $affiliator->referrals()->count();
        $activeReferrals      = $affiliator->referrals()
            ->whereHas('subscriptions', fn($q) => $q->where('status', 'active'))
            ->count();
        $newReferralsThisMonth = $affiliator->referrals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // === Commission Statistics (use correct column names) ===
        $totalEarned = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->whereIn('status', ['cleared', 'paid'])
            ->sum('commission_amount');

        $pendingCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('status', 'pending')
            ->sum('commission_amount');

        $thisMonthCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->whereIn('status', ['cleared', 'paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');

        // Level breakdown
        $level1Commissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('level', 1)
            ->sum('commission_amount');

        $level2Commissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('level', 2)
            ->sum('commission_amount');

        // === Withdrawal Statistics ===
        $totalWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->whereIn('status', ['approved', 'paid'])
            ->sum('amount');

        $pendingWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->where('status', 'pending')
            ->sum('amount');

        $recentWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->latest()
            ->limit(5)
            ->get();

        // === Recent Commissions ===
        $recentCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->with(['transaction', 'customer'])
            ->latest()
            ->limit(10)
            ->get();

        // === Downlines ===
        $topDownlines = $affiliator->downlines()
            ->withCount('referrals')
            ->orderByDesc('referrals_count')
            ->limit(5)
            ->get();

        // === Commission Trend (Last 6 months) ===
        $commissionTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month             = now()->subMonths($i);
            $commissionTrend[] = [
                'month'  => $month->format('M Y'),
                'amount' => AffiliateCommission::where('affiliator_id', $affiliator->id)
                    ->whereIn('status', ['cleared', 'paid'])
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('commission_amount'),
            ];
        }

        // === Performance Metrics ===
        $conversionRate = 0;
        if ($totalReferrals > 0) {
            $conversionRate = round(($activeReferrals / $totalReferrals) * 100, 2);
        }

        $averageCommission = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->whereIn('status', ['cleared', 'paid'])
            ->avg('commission_amount') ?? 0;

        // Available balance from wallet or affiliator.balance
        $availableBalance = (float) ($affiliator->balance ?? 0);

        // === Referral Monthly (Last 6 months) ===
        $referralMonthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $referralMonthly[] = [
                'month' => $month->format('M Y'),
                'count' => $affiliator->referrals()
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('affiliator.dashboard.index', [
            'stats' => [
                'totalReferrals'       => $totalReferrals,
                'activeReferrals'      => $activeReferrals,
                'newReferralsThisMonth' => $newReferralsThisMonth,
                'totalEarned'          => $totalEarned,
                'pendingCommissions'   => $pendingCommissions,
                'thisMonthCommissions' => $thisMonthCommissions,
                'level1Commissions'    => $level1Commissions,
                'level2Commissions'    => $level2Commissions,
                'availableBalance'     => $availableBalance,
                'totalWithdrawals'     => $totalWithdrawals,
                'pendingWithdrawals'   => $pendingWithdrawals,
                'conversionRate'       => $conversionRate,
                'averageCommission'    => $averageCommission,
                // Legacy keys for backward compatibility
                'totalBalance'         => $availableBalance,
                'totalCommissions'     => $totalEarned,
                'balance'              => $availableBalance,
                'pendingCommission'    => $pendingCommissions,
                'level1Count'          => $activeReferrals,
                'level2Count'          => $affiliator->downlines()->count(),
            ],
            'recentCommissions' => $recentCommissions,
            'recentWithdrawals' => $recentWithdrawals,
            'commissionTrend'   => $commissionTrend,
            'downlines'         => $topDownlines,
            'referralMonthly'   => $referralMonthly,
        ]);
    }
}
