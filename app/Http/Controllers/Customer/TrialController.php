<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ErpRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrialController extends Controller
{
    public function index(Request $request): View
    {
        $trials = $request->user()->erpRequests()
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('customer.trials.index', compact('trials'));
    }

    public function checkSubdomain(Request $request): \Illuminate\Http\JsonResponse
    {
        $subdomain = $request->input('subdomain');
        if (!$subdomain) {
            return response()->json(['available' => false, 'message' => 'Subdomain is required.']);
        }
        
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $subdomain)) {
            return response()->json(['available' => false, 'message' => 'Hanya huruf, angka, dan strip yang diperbolehkan.']);
        }

        $existsInRequests = ErpRequest::where('requested_subdomain', $subdomain)
            ->whereNotIn('status', [ErpRequest::STATUS_REJECTED, ErpRequest::STATUS_TRIAL_EXPIRED])
            ->exists();
            
        $domainStr = $subdomain . '.cooca.id';
        $existsInLicenses = \App\Models\License::where('domain', $domainStr)->exists();

        $exists = $existsInRequests || $existsInLicenses;
            
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Subdomain tidak tersedia.' : 'Subdomain tersedia.'
        ]);
    }

    public function create(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $customer = $request->user();
        if (!$customer->isCompanyProfileComplete()) {
            return redirect()->route('customer.company-profile.edit')
                ->with('error', 'Silakan lengkapi Profil Perusahaan Anda terlebih dahulu sebelum request trial.');
        }

        $products = Product::active()->get();

        return view('customer.trials.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = $request->user();
        if (!$customer->isCompanyProfileComplete()) {
            return redirect()->route('customer.company-profile.edit')
                ->with('error', 'Silakan lengkapi Profil Perusahaan Anda terlebih dahulu sebelum request trial.');
        }

        $validated = $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'requested_subdomain' => 'required|string|max:63|alpha_dash|unique:erp_requests,requested_subdomain',
            'notes' => 'nullable|string|max:1000',
        ]);

        $existing = ErpRequest::where('customer_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->whereNotIn('status', [ErpRequest::STATUS_REJECTED, ErpRequest::STATUS_TRIAL_EXPIRED])
            ->exists();

        if ($existing) {
            return redirect()->back()->withErrors(['product_id' => 'You already have an active trial for this product.']);
        }

        // Make sure the affiliator actually exists in customers table before assigning
        $affiliatorId = $request->user()->affiliator_id;
        if ($affiliatorId && !\App\Models\Customer::where('id', $affiliatorId)->exists()) {
            $affiliatorId = null;
        }

        $trial = ErpRequest::create([
            'customer_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'requested_subdomain' => $validated['requested_subdomain'],
            'status' => ErpRequest::STATUS_SUBMITTED,
            'notes' => $validated['notes'] ?? null,
            'affiliate_id' => $affiliatorId,
        ]);

        event(new \App\Events\Trial\TrialSubmitted($trial));

        return redirect()->route('customer.trials.index')->with('status', 'Trial request submitted successfully.');
    }

    public function show(Request $request, ErpRequest $trial): View
    {
        if ($request->user()->id !== $trial->customer_id) {
            abort(403);
        }
        $trial->load('product');

        return view('customer.trials.show', compact('trial'));
    }
}
