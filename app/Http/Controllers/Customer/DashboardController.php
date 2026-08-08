<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Customer Dashboard Controller
 *
 * Handles customer dashboard with subscription, license, invoice, and spending statistics.
 */
final class DashboardController extends Controller
{
    /**
     * Display customer dashboard with comprehensive statistics.
     */
    public function index(): View
    {
        $customer = Auth::user();
        $now      = Carbon::now();

        // ─────────────────────────────────────────────
        // LICENSE KPIs
        // ─────────────────────────────────────────────
        $activeLicenses = License::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->count();

        $expiringLicenses = License::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now->copy()->addDays(30))
            ->count();

        // Only count licenses that are active or belong to an active/completed subscription (paid)
        $totalLicenses = License::where('customer_id', $customer->id)
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('is_trial', true);
            })
            ->count();

        // ─────────────────────────────────────────────
        // SUBSCRIPTION KPIs
        // ─────────────────────────────────────────────
        // Only count subscriptions that have been paid (active, expired, cancelled) — not pending/trial
        $totalSubscriptions  = Subscription::where('customer_id', $customer->id)
            ->whereIn('status', ['active', 'expired', 'cancelled'])
            ->count();
        $activeSubscriptions = Subscription::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->count();
        $expiredSubscriptions = Subscription::where('customer_id', $customer->id)
            ->where('status', 'expired')
            ->count();

        // Upcoming renewals in next 14 days
        $upcomingRenewals = Subscription::where('customer_id', $customer->id)
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now->copy()->addDays(14))
            ->orderBy('expires_at')
            ->with(['subscriptionPlan'])
            ->get();

        // ─────────────────────────────────────────────
        // INVOICE KPIs
        // ─────────────────────────────────────────────
        $pendingInvoices = Invoice::where('customer_id', $customer->id)
            ->where('status', 'issued')
            ->count();

        $unpaidInvoicesAmount = Invoice::where('customer_id', $customer->id)
            ->where('status', 'issued')
            ->sum('amount');

        // ─────────────────────────────────────────────
        // SPENDING KPIs
        // ─────────────────────────────────────────────
        $totalSpent = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->sum('gross_amount');

        $thisMonthSpent = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->whereYear('paid_at', $now->year)
            ->whereMonth('paid_at', $now->month)
            ->sum('gross_amount');

        $totalTransactions = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->count();

        // ─────────────────────────────────────────────
        // SPENDING CHART — Last 6 Months
        // ─────────────────────────────────────────────
        $spendingChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $spendingChart[] = [
                'month'  => $month->format('M Y'),
                'amount' => (float) Transaction::where('customer_id', $customer->id)
                    ->where('status', 'paid')
                    ->whereYear('paid_at', $month->year)
                    ->whereMonth('paid_at', $month->month)
                    ->sum('gross_amount'),
            ];
        }

        // ─────────────────────────────────────────────
        // RECENT DATA
        // ─────────────────────────────────────────────
        $recentTransactions = Transaction::where('customer_id', $customer->id)
            ->with(['subscription.subscriptionPlan'])
            ->latest()
            ->limit(6)
            ->get();

        $recentLicenses = License::where('customer_id', $customer->id)
            ->with(['subscription.subscriptionPlan'])
            ->where(function ($q) {
                // Show active licenses, OR trial licenses (active_trial) without subscription check
                $q->where('status', 'active')
                  ->orWhere('is_trial', true);
            })
            ->orderBy('expires_at')
            ->limit(5)
            ->get();

        // ─────────────────────────────────────────────
        // NOTIFICATIONS
        // ─────────────────────────────────────────────
        try {
            $notifications = $customer->notifications()
                ->unread()
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            $notifications = collect();
        }

        return view('customer.dashboard.index', [
            'stats' => [
                'activeLicenses'      => $activeLicenses,
                'totalLicenses'       => $totalLicenses,
                'expiringLicenses'    => $expiringLicenses,
                'totalSubscriptions'  => $totalSubscriptions,
                'activeSubscriptions' => $activeSubscriptions,
                'expiredSubscriptions'=> $expiredSubscriptions,
                'pendingInvoices'     => $pendingInvoices,
                'unpaidInvoicesAmount'=> $unpaidInvoicesAmount,
                'totalSpent'          => $totalSpent,
                'thisMonthSpent'      => $thisMonthSpent,
                'totalTransactions'   => $totalTransactions,
            ],
            'recentTransactions' => $recentTransactions,
            'recentLicenses'     => $recentLicenses,
            'upcomingRenewals'   => $upcomingRenewals,
            'notifications'      => $notifications,
            'spendingChart'      => $spendingChart,
        ]);
    }
}
