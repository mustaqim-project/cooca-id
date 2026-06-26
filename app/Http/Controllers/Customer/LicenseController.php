<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LicenseResource;
use App\Services\License\LicenseService;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
final class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService
    ) {}

    /**
     * Display listing of customer licenses.
     */
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();
        $licenses = \App\Models\License::where('customer_id', $customer->getKey())->get();

        return view('customer.licenses.index', [
            'licenses' => LicenseResource::collection($licenses),
        ]);
    }

    /**
     * Display the specified license details.
     */
    public function show(string $id): View
    {
        $customer = Auth::guard('customer')->user();
        $license = \App\Models\License::where('id', $id)->where('customer_id', $customer->getKey())->first();

        if (!$license) {
            abort(404, 'License not found');
        }

        return view('customer.licenses.show', [
            'license' => new LicenseResource($license),
        ]);
    }

    /**
     * Activate license for customer.
     */
    public function activate(string $id): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        try {
            $license = \App\Models\License::where('id', $id)->where('customer_id', $customer->getKey())->firstOrFail();
            $license->update(['status' => 'active', 'activated_at' => now()]);
            
            return redirect()->route('customer.licenses.credentials', $license->id)
                ->with('success', 'License activated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to activate license: ' . $e->getMessage()]);
        }
    }

    /**
     * Display license credentials (license code + token).
     */
    public function credentials(string $id): View
    {
        $customer = Auth::guard('customer')->user();
        $license = \App\Models\License::where('id', $id)->where('customer_id', $customer->getKey())->first();

        if (!$license) {
            abort(404, 'License not found');
        }

        return view('customer.licenses.credentials', [
            'license' => new LicenseResource($license),
        ]);
    }
}
