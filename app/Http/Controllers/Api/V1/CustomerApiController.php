<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ErpRequest;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    // GET /api/v1/customer/profile
    public function profile(Request $request): JsonResponse
    {
        $customer = $request->user();
        $customer->load('companyProfile');
        return response()->json(['data' => $customer]);
    }

    // PUT /api/v1/customer/profile
    public function updateProfile(Request $request): JsonResponse
    {
        $customer = $request->user();
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'business_name' => 'sometimes|string|max:255',
        ]);
        $customer->update($validated);
        return response()->json(['data' => $customer->fresh()]);
    }

    // GET/PUT /api/v1/customer/company
    public function company(Request $request): JsonResponse
    {
        $customer = $request->user();
        return response()->json(['data' => $customer->companyProfile]);
    }

    public function updateCompany(Request $request): JsonResponse
    {
        $customer = $request->user();
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|in:1-10,11-50,51-200,201-500,500+',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'npwp' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
        ]);

        $profile = CompanyProfile::updateOrCreate(
            ['customer_id' => $customer->id],
            $validated
        );

        return response()->json(['data' => $profile]);
    }

    // GET /api/v1/customer/trials
    public function trials(Request $request): JsonResponse
    {
        $trials = $request->user()->erpRequests()
            ->with('product')
            ->latest()
            ->paginate(15);

        return response()->json($trials);
    }

    // POST /api/v1/customer/trials
    public function requestTrial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'requested_subdomain' => 'required|string|max:63|alpha_dash|unique:erp_requests,requested_subdomain',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check existing active trial for same product
        $existing = ErpRequest::where('customer_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->whereNotIn('status', [ErpRequest::STATUS_REJECTED, ErpRequest::STATUS_TRIAL_EXPIRED])
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Already have active trial for this product.'], 422);
        }

        $trial = ErpRequest::create([
            'customer_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'requested_subdomain' => $validated['requested_subdomain'],
            'status' => ErpRequest::STATUS_SUBMITTED,
            'notes' => $validated['notes'] ?? null,
            'affiliate_id' => $request->user()->referred_by_id,
        ]);

        event(new \App\Events\Trial\TrialSubmitted($trial));

        return response()->json(['data' => $trial], 201);
    }

    // GET /api/v1/customer/subscriptions
    public function subscriptions(Request $request): JsonResponse
    {
        $subs = $request->user()->subscriptions()
            ->with('subscriptionPlan.product', 'license')
            ->latest()
            ->paginate(15);

        return response()->json($subs);
    }

    // GET /api/v1/customer/invoices
    public function invoices(Request $request): JsonResponse
    {
        $invoices = $request->user()->invoices()
            ->latest()
            ->paginate(15);

        return response()->json($invoices);
    }

    // GET /api/v1/customer/licenses
    public function licenses(Request $request): JsonResponse
    {
        $licenses = $request->user()->licenses()
            ->with('product')
            ->latest()
            ->paginate(15);

        return response()->json($licenses);
    }

    // GET /api/v1/customer/tickets
    public function tickets(Request $request): JsonResponse
    {
        $tickets = $request->user()->tickets()
            ->latest()
            ->paginate(15);

        return response()->json($tickets);
    }

    // POST /api/v1/customer/tickets
    public function createTicket(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $ticket = $request->user()->tickets()->create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        event(new \App\Events\Ticket\TicketCreated($ticket));

        return response()->json(['data' => $ticket], 201);
    }
}
