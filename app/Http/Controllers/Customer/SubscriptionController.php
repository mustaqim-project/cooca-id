<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\CreateSubscriptionAction;
use App\DTOs\SubscriptionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateSubscriptionRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
        private readonly CreateSubscriptionAction $createSubscriptionAction
    ) {}

    /**
     * Store a newly created subscription.
     */
    public function store(CreateSubscriptionRequest $request): JsonResponse
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
    public function cancel(string $id): JsonResponse
    {
        $customer = Auth::guard('customer')->user();
        $subscription = $this->subscriptionService->findByIdAndCustomer($id, $customer->getKey());

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $this->subscriptionService->cancel($id);

        return response()->json([
            'message' => 'Subscription cancelled successfully',
        ]);
    }

    /**
     * Renew the specified subscription.
     */
    public function renew(string $id): JsonResponse
    {
        $customer = Auth::guard('customer')->user();
        $subscription = $this->subscriptionService->findByIdAndCustomer($id, $customer->getKey());

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $this->subscriptionService->renew($id);

        return response()->json([
            'message' => 'Subscription renewed successfully',
        ]);
    }
}
