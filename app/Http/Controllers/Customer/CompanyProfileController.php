<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $customer = $request->user();
        $companyProfile = $customer->companyProfile ?? new CompanyProfile();

        return view('customer.company-profile.edit', compact('companyProfile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user();
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'company_size' => 'required|in:1-10,11-50,51-200,201-500,500+',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'npwp' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
        ]);

        CompanyProfile::updateOrCreate(
            ['customer_id' => $customer->id],
            $validated
        );

        return redirect()->back()->with('status', 'Company profile updated successfully.');
    }
}
