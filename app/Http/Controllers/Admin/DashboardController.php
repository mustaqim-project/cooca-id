<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\License;
use App\Models\Transaction;
use App\Models\AffiliateWithdrawal;
use App\Models\AffiliateCommission;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Admin Dashboard Controller
 *
 * Handles admin dashboard with comprehensive KPI statistics,
 * chart data, and activity feeds.
 */
final class DashboardController extends Controller
{
    /**
     * Display admin dashboard with comprehensive statistics.
     */
    public function index(): View
    {
        $now   = Carbon::now();
        $thisMonthStart  = $now->copy()->startOfMonth();
        $lastMonthStart  = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd    = $now->copy()->subMonth()->endOfMonth();
        $todayStart      = $now->copy()->startOfDay();
        $thisYearStart   = $now->copy()->startOfYear();

        // ─────────────────────────────────────────────
        // REVENUE KPIs
        // ─────────────────────────────────────────────
        $totalRevenue = Transaction::where('status', 'paid')->sum('net_amount');

        $monthlyRevenue = Transaction::where('status', 'paid')
            ->whereBetween('paid_at', [$thisMonthStart, $now])
            ->sum('net_amount');

        $lastMonthRevenue = Transaction::where('status', 'paid')
            ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('net_amount');

        $todayRevenue = Transaction::where('status', 'paid')
            ->where('paid_at', '>=', $todayStart)
            ->sum('net_amount');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($monthlyRevenue > 0 ? 100 : 0);

        // ─────────────────────────────────────────────
        // CUSTOMER KPIs
        // ─────────────────────────────────────────────
        $totalCustomers = Customer::count();

        $newCustomersThisMonth = Customer::where('created_at', '>=', $thisMonthStart)->count();

        $newCustomersLastMonth = Customer::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $customerGrowth = $newCustomersLastMonth > 0
            ? round((($newCustomersThisMonth - $newCustomersLastMonth) / $newCustomersLastMonth) * 100, 1)
            : ($newCustomersThisMonth > 0 ? 100 : 0);

        // ─────────────────────────────────────────────
        // SUBSCRIPTION KPIs
        // ─────────────────────────────────────────────
        $activeSubscriptions  = Subscription::where('status', 'active')->count();
        $totalSubscriptions   = Subscription::count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $trialSubscriptions   = Subscription::where('status', 'trial')->count();

        $newSubscriptionsThisMonth = Subscription::where('created_at', '>=', $thisMonthStart)->count();

        // ─────────────────────────────────────────────
        // LICENSE KPIs
        // ─────────────────────────────────────────────
        $totalLicenses   = License::count();
        $activeLicenses  = License::where('status', 'active')->count();
        $expiredLicenses = License::where('status', 'expired')->count();

        // ─────────────────────────────────────────────
        // AFFILIATE KPIs
        // ─────────────────────────────────────────────
        $totalAffiliators = Affiliator::count();
        $activeAffiliators = Affiliator::whereHas('referrals')->count();

        $pendingWithdrawals       = AffiliateWithdrawal::where('status', 'pending')->count();
        $pendingWithdrawalAmount  = AffiliateWithdrawal::where('status', 'pending')->sum('amount');

        $totalCommissionsPaid = AffiliateCommission::where('status', 'paid')->sum('commission_amount');
        $totalCommissionsPending = AffiliateCommission::where('status', 'pending')->sum('commission_amount');

        // ─────────────────────────────────────────────
        // TRANSACTION KPIs
        // ─────────────────────────────────────────────
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $failedTransactions  = Transaction::where('status', 'failed')->count();
        $paidTransactions    = Transaction::where('status', 'paid')->count();

        $todayTransactions = Transaction::where('created_at', '>=', $todayStart)->count();

        // Transaction status breakdown (for donut chart)
        $transactionStatusBreakdown = Transaction::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ─────────────────────────────────────────────
        // REVENUE CHART DATA (last 12 months)
        // ─────────────────────────────────────────────
        $revenueChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $revenue = Transaction::where('status', 'paid')
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->sum('net_amount');

            $revenueChartData[] = [
                'month'   => $month->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        // ─────────────────────────────────────────────
        // CUSTOMER GROWTH CHART DATA (last 12 months)
        // ─────────────────────────────────────────────
        $customerChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $count = Customer::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $customerChartData[] = [
                'month' => $month->format('M Y'),
                'count' => $count,
            ];
        }

        // ─────────────────────────────────────────────
        // DAILY REVENUE CHART (last 30 days)
        // ─────────────────────────────────────────────
        $dailyRevenueData = [];
        for ($i = 29; $i >= 0; $i--) {
            $day     = $now->copy()->subDays($i);
            $revenue = Transaction::where('status', 'paid')
                ->whereDate('paid_at', $day->toDateString())
                ->sum('net_amount');

            $dailyRevenueData[] = [
                'date'    => $day->format('d M'),
                'revenue' => (float) $revenue,
            ];
        }

        // ─────────────────────────────────────────────
        // TOP PRODUCTS (for bar chart)
        // ─────────────────────────────────────────────
        $topProducts = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->join('products', 'subscription_plans.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('COUNT(subscriptions.id) as count')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('count')
            ->limit(6)
            ->get();

        // ─────────────────────────────────────────────
        // SUBSCRIPTION PLAN DISTRIBUTION (donut chart)
        // ─────────────────────────────────────────────
        $subscriptionPlanDist = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->select('subscription_plans.name', DB::raw('COUNT(subscriptions.id) as count'))
            ->where('subscriptions.status', 'active')
            ->groupBy('subscription_plans.id', 'subscription_plans.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // ─────────────────────────────────────────────
        // RECENT ACTIVITIES
        // ─────────────────────────────────────────────
        $recentTransactions = Transaction::with(['customer'])
            ->latest()
            ->limit(8)
            ->get();

        $recentWithdrawals = AffiliateWithdrawal::with(['affiliator'])
            ->latest()
            ->limit(5)
            ->get();

        $recentCustomers = Customer::latest()
            ->limit(5)
            ->get();

        // Recent ERP Requests
        $recentErpRequests = \App\Models\ErpRequest::with(['customer'])
            ->latest()
            ->limit(5)
            ->get();

        // ─────────────────────────────────────────────
        // COMMISSION MONTHLY TREND (last 6 months)
        // ─────────────────────────────────────────────
        $commissionTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $amount = AffiliateCommission::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('commission_amount');

            $commissionTrend[] = [
                'month'  => $month->format('M Y'),
                'amount' => (float) $amount,
            ];
        }

        return view('admin.dashboard.index', [
            'stats' => [
                // Revenue
                'totalRevenue'          => $totalRevenue,
                'monthlyRevenue'        => $monthlyRevenue,
                'lastMonthRevenue'      => $lastMonthRevenue,
                'todayRevenue'          => $todayRevenue,
                'revenueGrowth'         => $revenueGrowth,
                // Customers
                'totalCustomers'        => $totalCustomers,
                'newCustomersThisMonth' => $newCustomersThisMonth,
                'customerGrowth'        => $customerGrowth,
                // Subscriptions
                'activeSubscriptions'   => $activeSubscriptions,
                'totalSubscriptions'    => $totalSubscriptions,
                'expiredSubscriptions'  => $expiredSubscriptions,
                'trialSubscriptions'    => $trialSubscriptions,
                'newSubscriptionsThisMonth' => $newSubscriptionsThisMonth,
                // Licenses
                'totalLicenses'         => $totalLicenses,
                'activeLicenses'        => $activeLicenses,
                'expiredLicenses'       => $expiredLicenses,
                // Affiliates
                'totalAffiliators'      => $totalAffiliators,
                'activeAffiliators'     => $activeAffiliators,
                'pendingWithdrawals'    => $pendingWithdrawals,
                'pendingWithdrawalAmount' => $pendingWithdrawalAmount,
                'totalCommissionsPaid'  => $totalCommissionsPaid,
                'totalCommissionsPending' => $totalCommissionsPending,
                // Transactions
                'pendingTransactions'   => $pendingTransactions,
                'failedTransactions'    => $failedTransactions,
                'paidTransactions'      => $paidTransactions,
                'todayTransactions'     => $todayTransactions,
            ],
            'recentTransactions'        => $recentTransactions,
            'recentWithdrawals'         => $recentWithdrawals,
            'recentCustomers'           => $recentCustomers,
            'recentErpRequests'        => $recentErpRequests,
            'revenueChartData'          => $revenueChartData,
            'customerChartData'         => $customerChartData,
            'dailyRevenueData'          => $dailyRevenueData,
            'topProducts'               => $topProducts,
            'subscriptionPlanDist'      => $subscriptionPlanDist,
            'transactionStatusBreakdown' => $transactionStatusBreakdown,
            'commissionTrend'           => $commissionTrend,
        ]);
    }
}
