<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LicenseActivation;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki license aktif
        $license = LicenseActivation::where('customer_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$license) {
            // Jika tidak ada lisensi, cek apakah sedang dalam proses trial request
            $hasPendingRequest = $user->erpRequests()
                ->whereIn('status', ['Submitted', 'WaitingApproval', 'WaitingSetup', 'InSetup', 'DomainSetup', 'Testing'])
                ->exists();

            if ($hasPendingRequest) {
                return redirect()->route('customer.trial.status')
                    ->with('info', 'Your trial application is being processed. Please wait for approval.');
            }

            return redirect()->route('customer.subscription.index')
                ->with('error', 'No active license found. Please subscribe or start a trial.');
        }

        // Cek masa berlaku
        if ($license->expires_at && $license->expires_at->isPast()) {
            // Update status license jika expired tapi belum terupdate
            $license->update(['status' => 'expired']);
            
            return redirect()->route('customer.subscription.expired')
                ->with('error', 'Your license has expired. Please renew your subscription.');
        }

        // Attach license to request for easy access in controllers
        $request->merge(['active_license' => $license]);

        return $next($request);
    }
}
