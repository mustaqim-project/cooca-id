<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(Request $request): View
    {
        $tenants = $request->user()->tenants()
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('customer.domains.index', compact('tenants'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        if ($request->user()->id !== $tenant->customer_id) {
            abort(403);
        }

        $validated = $request->validate([
            'custom_domain' => 'required|string|max:255|unique:tenants,custom_domain,' . $tenant->id,
        ]);

        $tenant->update([
            'custom_domain' => $validated['custom_domain'],
        ]);

        return redirect()->back()->with('status', 'Custom domain updated successfully.');
    }

    public function verify(Request $request, Tenant $tenant): RedirectResponse
    {
        if ($request->user()->id !== $tenant->customer_id) {
            abort(403);
        }

        if (!$tenant->custom_domain) {
            return redirect()->back()->withErrors(['custom_domain' => 'No custom domain set.']);
        }

        // Basic check for DNS resolution (in real production, check CNAME record points to server_ip/host)
        $resolved = @dns_get_record($tenant->custom_domain, DNS_A + DNS_CNAME);

        if ($resolved && count($resolved) > 0) {
            // Verified
            return redirect()->back()->with('status', 'Domain verified successfully.');
        }

        return redirect()->back()->withErrors(['custom_domain' => 'Domain verification failed. DNS records not found.']);
    }
}
