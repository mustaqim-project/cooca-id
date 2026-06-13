<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Customer Dashboard Controller
 *
 * Handles customer dashboard with subscription, license, and invoice statistics.
 */
final class DashboardController extends Controller
{
    /**
     * Display customer dashboard with comprehensive statistics.
     */
    public function index(): Response
    {
        $customer = Auth::guard('customer')->user();

        // License Statistics
        $activeLicenses = License::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->count();

        $expiringLicenses = License::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(30))
            ->count();

        // Subscription Statistics
        $totalSubscriptions = Subscription::where('customer_id', $customer->id)->count();
        $activeSubscriptions = Subscription::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->count();

        // Invoice Statistics
        $pendingInvoices = Invoice::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->count();

        $unpaidInvoicesAmount = Invoice::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Transaction Statistics
        $totalSpent = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->sum('gross_amount');

        $recentTransactions = Transaction::where('customer_id', $customer->id)
            ->with(['subscription'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent Licenses
        $recentLicenses = License::where('customer_id', $customer->id)
            ->with(['subscription.product'])
            ->latest()
            ->limit(5)
            ->get();

        // Upcoming Renewals
        $upcomingRenewals = Subscription::where('customer_id', $customer->id)
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays(14))
            ->get();

        // Notifications
        $notifications = $customer->notifications()
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Customer/Dashboard/Index', [
            'stats' => [
                'active_licenses' => $activeLicenses,
                'expiring_licenses' => $expiringLicenses,
                'total_subscriptions' => $totalSubscriptions,
                'active_subscriptions' => $activeSubscriptions,
                'pending_invoices' => $pendingInvoices,
                'unpaid_invoices_amount' => $unpaidInvoicesAmount,
                'total_spent' => $totalSpent,
            ],
            'recentTransactions' => $recentTransactions,
            'recentLicenses' => $recentLicenses,
            'upcomingRenewals' => $upcomingRenewals,
            'notifications' => $notifications,
        ]);
    }
}
