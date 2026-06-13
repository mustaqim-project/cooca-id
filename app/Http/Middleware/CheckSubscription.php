<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
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

        // Cek apakah user memiliki subscription aktif
        $subscription = Subscription::where('customer_id', $user->id)
            ->whereIn('status', ['Active', 'Trial'])
            ->first();

        if (!$subscription) {
            // Jika tidak ada subscription, cek apakah sedang dalam proses trial request
            $hasPendingRequest = $user->erpRequests()
                ->whereIn('status', ['Submitted', 'WaitingApproval', 'WaitingSetup', 'InSetup', 'DomainSetup', 'Testing'])
                ->exists();

            if ($hasPendingRequest) {
                return redirect()->route('customer.trial.status')
                    ->with('info', 'Your trial application is being processed. Please wait for approval.');
            }

            return redirect()->route('customer.subscription.index')
                ->with('error', 'No active subscription found. Please subscribe or start a trial.');
        }

        // Cek masa berlaku subscription
        if ($subscription->ends_at && $subscription->ends_at->isPast()) {
            // Update status subscription jika expired tapi belum terupdate
            $subscription->update(['status' => 'Expired']);
            
            return redirect()->route('customer.subscription.expired')
                ->with('error', 'Your subscription has expired. Please renew your subscription.');
        }

        // Attach subscription to request for easy access in controllers
        $request->merge(['active_subscription' => $subscription]);

        return $next($request);
    }
}
