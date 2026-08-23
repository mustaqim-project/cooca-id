<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LicenseResource;
use App\Services\License\LicenseService;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

final class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService
    ) {}

    /**
     * Display listing of customer licenses.
     */
    public function index()
    {
        $customer = Auth::user();
        $licenses = \App\Models\License::with(['product', 'subscriptionPlan', 'subscription', 'customer'])
            ->where('customer_id', $customer->getKey())
            ->where(function ($query) {
                $query->where('status', '!=', 'inactive')
                      ->orWhereHas('subscription', function ($q) {
                          $q->whereIn('status', ['active', 'trialing']);
                      });
            })
            ->latest()
            ->get();

        return view('customer.licenses.index', [
            'licenses' => LicenseResource::collection($licenses),
        ]);
    }

    /**
     * Display the specified license details.
     */
    public function show(string $id)
    {
        $customer = Auth::user();
        $license = \App\Models\License::with(['product', 'subscriptionPlan', 'subscription', 'customer'])
            ->where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->first();

        if (!$license) {
            abort(404, 'License not found');
        }

        Gate::authorize('view', $license);

        return view('customer.licenses.show', [
            'license' => new LicenseResource($license),
        ]);
    }

    /**
     * Activate license for customer.
     */
    public function activate(string $id)
    {
        $customer = Auth::user();
        
        try {
            $license = \App\Models\License::where('id', $id)
                ->where('customer_id', $customer->getKey())
                ->where('status', 'inactive')
                ->whereHas('subscription', function ($q) {
                    $q->where('status', 'active');
                })
                ->firstOrFail();

            Gate::authorize('update', $license);

            $subscription = $license->subscription;
            $startsAt = now();

            $license->update([
                'status' => 'active',
                'activated_at' => $startsAt,
                'starts_at' => $subscription->started_at ?? $startsAt,
                'expires_at' => $subscription->expires_at,
            ]);
            
            return redirect()->route('customer.licenses.credentials', $license->id)
                ->with('success', 'License activated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengaktifkan lisensi: Pembayaran belum diselesaikan atau lisensi tidak valid.']);
        }
    }

    /**
     * Display license credentials (license code + token).
     */
    public function credentials(string $id)
    {
        $customer = Auth::user();
        $license = \App\Models\License::with(['product', 'subscriptionPlan', 'subscription', 'customer'])
            ->where('id', $id)
            ->where('customer_id', $customer->getKey())
            ->first();

        if (!$license) {
            abort(404, 'License not found');
        }

        Gate::authorize('view', $license);

        return view('customer.licenses.credentials', [
            'license' => new LicenseResource($license),
        ]);
    }
}
