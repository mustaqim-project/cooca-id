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

        $product = \App\Models\Product::where('slug', $data['product_slug'])->firstOrFail();
        
        // Ensure proper domain format
        $domainStr = $data['domain'];
        if (!str_contains($domainStr, '.')) {
            $domainStr .= '.cooca.id';
        }

        $domain = \App\Models\Domain::firstOrCreate(
            ['domain' => $domainStr],
            [
                'customer_id' => $customer->getKey(),
                'type' => str_contains($domainStr, 'cooca.id') ? \App\Models\Domain::TYPE_SUBDOMAIN : \App\Models\Domain::TYPE_CUSTOM_DOMAIN,
                'status' => \App\Models\Domain::STATUS_PENDING,
            ]
        );

        // Find existing license or create (License is generated only 1 time forever)
        $license = \App\Models\License::where('customer_id', $customer->getKey())
            ->where('domain_id', $domain->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$license) {
            do {
                $licenseCode = strtoupper(\Illuminate\Support\Str::random(16));
            } while (\App\Models\License::where('license_code', $licenseCode)->exists());

            do {
                $tokenCode = strtoupper(\Illuminate\Support\Str::random(16));
            } while (\App\Models\License::where('token_code', $tokenCode)->exists());

            $license = \App\Models\License::create([
                'customer_id' => $customer->getKey(),
                'product_id' => $product->id,
                'subscription_plan_id' => $data['subscription_plan_id'],
                'domain_id' => $domain->id,
                'license_code' => $licenseCode,
                'token_code' => $tokenCode,
                'domain' => $domain->domain,
                'status' => \App\Models\License::STATUS_INACTIVE,
                'is_trial' => false,
            ]);
        }

        $subscriptionData = SubscriptionData::from([
            'customer_id' => $customer->getKey(),
            'license_id' => $license->id,
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
