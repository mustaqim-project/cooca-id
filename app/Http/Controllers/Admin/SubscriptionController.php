<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\License;
use Illuminate\Http\Request;


/**
 * Admin Subscription Controller
 * 
 * Manages customer subscriptions from admin panel.
 */
class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['customer', 'subscriptionPlan.product', 'license'])
            ->latest('created_at');

        // Filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($customer = $request->get('customer_id')) {
            $query->where('customer_id', $customer);
        }

        if ($product = $request->get('product_id')) {
            $query->whereHas('subscriptionPlan', function ($q) use ($product) {
                $q->where('product_id', $product);
            });
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'trial' => Subscription::where('status', 'trial')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
        ];

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'stats' => $stats,
            'filters' => [
                'status' => $request->get('status'),
                'customer_id' => $request->get('customer_id'),
                'product_id' => $request->get('product_id'),
            ],
        ]);
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['customer', 'subscriptionPlan.product', 'license', 'transactions']);

        $timeline = [];
        
        // Add subscription creation event
        $timeline[] = [
            'date' => $subscription->created_at,
            'event' => 'Subscription Created',
            'description' => "Subscription {$subscription->status} created",
        ];

        // Add activation event if exists
        if ($subscription->activated_at) {
            $timeline[] = [
                'date' => $subscription->activated_at,
                'event' => 'Subscription Activated',
                'description' => 'Subscription was activated',
            ];
        }

        // Add expiry event if exists
        if ($subscription->expires_at) {
            $timeline[] = [
                'date' => $subscription->expires_at,
                'event' => 'Subscription Expires',
                'description' => 'Subscription will expire/will have expired',
            ];
        }

        return view('admin.subscriptions.show', [
            'subscription' => $subscription,
            'timeline' => collect($timeline)->sortByDesc('date')->values()->all(),
        ]);
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(Subscription $subscription, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'immediate' => 'boolean',
        ]);

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
        ]);

        // If immediate, also revoke license
        if ($request->boolean('immediate') && $subscription->license) {
            $subscription->license->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);
        }

        // Send notification to customer
        $subscription->customer->notify(new \App\Notifications\Customer\SubscriptionCancelledNotification(
            $subscription,
            $request->reason
        ));

        return back()->with('success', 'Subscription cancelled successfully.');
    }
}
