<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\License;
use App\Models\Transaction;
use App\Models\AffiliateWithdrawal;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Admin Dashboard Controller
 * 
 * Handles admin dashboard with statistics and overview data.
 */
final class DashboardController extends Controller
{
    /**
     * Display admin dashboard with comprehensive statistics.
     */
    public function index()
    {
        // Revenue Statistics
        $totalRevenue = Transaction::where('status', 'paid')->sum('net_amount');
        $monthlyRevenue = Transaction::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('net_amount');
        
        // Customer Statistics
        $totalCustomers = Customer::count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)->count();
        
        // Affiliator Statistics
        $totalAffiliators = Affiliator::count();
        $activeAffiliators = Affiliator::whereHas('referrals')->count();
        
        // License Statistics
        $totalLicenses = License::count();
        $activeLicenses = License::where('status', 'active')->count();
        $expiredLicenses = License::where('status', 'expired')->count();
        
        // Subscription Statistics
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        
        // Transaction Statistics
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $failedTransactions = Transaction::where('status', 'failed')->count();
        
        // Withdrawal Statistics
        $pendingWithdrawals = AffiliateWithdrawal::where('status', 'pending')->count();
        $pendingWithdrawalAmount = AffiliateWithdrawal::where('status', 'pending')->sum('amount');
        
        // Recent Activities
        $recentTransactions = Transaction::with(['customer', 'subscription'])
            ->latest()
            ->limit(10)
            ->get();
        
        $recentWithdrawals = AffiliateWithdrawal::with(['affiliator'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Sales Chart Data (Last 6 months)
        $salesChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $salesChartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => Transaction::where('status', 'paid')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('net_amount'),
                'customers' => Customer::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }
        
        // Top Products
        $topProducts = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->join('products', 'subscription_plans.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('COUNT(subscriptions.id) as count'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', [
            'stats' => [
                'totalCustomers' => $totalCustomers,
                'totalAffiliators' => $totalAffiliators,
                'totalLicenses' => $totalLicenses,
                'totalRevenue' => $totalRevenue,
                'monthlyRevenue' => $monthlyRevenue,
                'newCustomersThisMonth' => $newCustomersThisMonth,
                'activeAffiliators' => $activeAffiliators,
                'activeLicenses' => $activeLicenses,
                'expiredLicenses' => $expiredLicenses,
                'totalSubscriptions' => $totalSubscriptions,
                'activeSubscriptions' => $activeSubscriptions,
                'pendingTransactions' => $pendingTransactions,
                'failedTransactions' => $failedTransactions,
                'pendingWithdrawals' => $pendingWithdrawals,
                'pendingWithdrawalAmount' => $pendingWithdrawalAmount,
                'revenueChange' => 0,
            ],
            'recentTransactions' => $recentTransactions,
            'recentWithdrawals' => $recentWithdrawals,
            'salesChartData' => $salesChartData,
            'topProducts' => $topProducts,
        ]);
    }
}
