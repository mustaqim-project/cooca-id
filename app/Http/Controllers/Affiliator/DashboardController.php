<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

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
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        
        // Referral Statistics
        $totalReferrals = $affiliator->referrals()->count();
        $activeReferrals = $affiliator->referrals()
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'active');
            })
            ->count();
        
        $newReferralsThisMonth = $affiliator->referrals()
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // Commission Statistics
        $totalCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('status', 'paid')
            ->sum('amount');
        
        $pendingCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('status', 'pending')
            ->sum('amount');
        
        $thisMonthCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        // Level 2 Commissions (Downline)
        $level2Commissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('level', 2)
            ->where('status', 'paid')
            ->sum('amount');
        
        // Withdrawal Statistics
        $totalWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->where('status', 'approved')
            ->sum('amount');
        
        $pendingWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->where('status', 'pending')
            ->sum('amount');
        
        $recentWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliator->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Recent Commissions
        $recentCommissions = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->with(['referral', 'subscription.product'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Top Referrers (for downline tracking)
        $topReferrers = $affiliator->downlines()
            ->withCount('referrals')
            ->orderByDesc('referrals_count')
            ->limit(5)
            ->get();
        
        // Commission Trend (Last 6 months)
        $commissionTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $commissionTrend[] = [
                'month' => $month->format('M Y'),
                'amount' => AffiliateCommission::where('affiliator_id', $affiliator->id)
                    ->where('status', 'paid')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount'),
            ];
        }
        
        // Performance Metrics
        $conversionRate = 0;
        $totalClicks = $affiliator->referral_clicks ?? 0;
        if ($totalClicks > 0 && $totalReferrals > 0) {
            $conversionRate = round(($totalReferrals / $totalClicks) * 100, 2);
        }
        
        $averageCommission = AffiliateCommission::where('affiliator_id', $affiliator->id)
            ->where('status', 'paid')
            ->avg('amount') ?? 0;

        return Inertia::render('Affiliator/Dashboard/Index', [
            'stats' => [
                'totalReferrals' => $totalReferrals,
                'activeReferrals' => $activeReferrals,
                'newReferralsThisMonth' => $newReferralsThisMonth,
                'totalCommissions' => $totalCommissions,
                'pendingCommissions' => $pendingCommissions,
                'thisMonthCommissions' => $thisMonthCommissions,
                'level2Commissions' => $level2Commissions,
                'balance' => $affiliator->balance ?? 0,
                'totalWithdrawals' => $totalWithdrawals,
                'pendingWithdrawals' => $pendingWithdrawals,
                'conversionRate' => $conversionRate,
                'averageCommission' => $averageCommission,
                'totalBalance' => $affiliator->balance ?? 0,
                'totalEarned' => $totalCommissions,
                'pendingCommission' => $pendingCommissions,
                'level1Count' => $activeReferrals,
                'level2Count' => 0,
            ],
            'commissions' => $recentCommissions,
            'referrals' => [],
            'downlines' => $topReferrers,
            'recentCommissions' => $recentCommissions,
            'recentWithdrawals' => $recentWithdrawals,
            'commissionTrend' => $commissionTrend,
        ]);
    }
}
