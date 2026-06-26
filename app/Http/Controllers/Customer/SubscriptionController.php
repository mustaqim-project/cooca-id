<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\Subscription\CreateSubscription\CreateSubscriptionAction;
use App\DTOs\SubscriptionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateSubscriptionRequest;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CreateSubscriptionAction $createSubscriptionAction
    ) {}

    public function index()
    {
        $subscriptions = \App\Models\Subscription::where('customer_id', Auth::guard('customer')->id())
            ->paginate(15);
            
        return view('customer.subscriptions.index', [
            'subscriptions' => $subscriptions
        ]);
    }

    public function create()
    {
        return view('customer.subscriptions.create');
    }

    public function show(string $subscription)
    {
        $subscription = \App\Models\Subscription::where('id', $subscription)
            ->where('customer_id', Auth::guard('customer')->id())
            ->first();
        
        if (!$subscription) {
            abort(404);
        }

        return view('customer.subscriptions.show', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Store a newly created subscription.
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $customer = Auth::guard('customer')->user();
        $data = $request->validated();

        $subscriptionData = SubscriptionData::from([
            'customer_id' => $customer->getKey(),
            'license_id' => $data['license_id'] ?? null,
            'subscription_plan_id' => $data['subscription_plan_id'],
            'started_at' => $data['started_at'] ?? now(),
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        $subscription = ($this->createSubscriptionAction)($subscriptionData);

        return response()->json([
            'message' => 'Subscription created successfully',
            'data' => $subscription,
        ], 201);
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(string $id)
    {
        $customer = Auth::guard('customer')->user();
        $subscription = \App\Models\Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $this->subscriptionService->cancelSubscription($subscription);

        return response()->json([
            'message' => 'Subscription cancelled successfully',
        ]);
    }

    /**
     * Renew the specified subscription.
     */
    public function renew(string $id)
    {
        $customer = Auth::guard('customer')->user();
        $subscription = \App\Models\Subscription::where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Renew for 1 month by default
        $this->subscriptionService->renewSubscription($subscription, 1);

        return response()->json([
            'message' => 'Subscription renewed successfully',
        ]);
    }
}
