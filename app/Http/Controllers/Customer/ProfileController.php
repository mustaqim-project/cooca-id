<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


/**
 * Customer Profile Controller
 * 
 * Manages customer profile settings.
 */
class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        $customer = Auth::user();

        return view('customer.profile.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the customer profile.
     */
    public function update(Request $request)
    {
        $customer = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        // Synchronize with Company Profile
        if ($customer->companyProfile) {
            $customer->companyProfile->update([
                'company_name' => $validated['business_name'] ?? $customer->companyProfile->company_name,
                'phone' => $validated['phone'] ?? $customer->companyProfile->phone,
            ]);
        } else {
            $customer->companyProfile()->create([
                'company_name' => $validated['business_name'] ?? $customer->name,
                'phone' => $validated['phone'] ?? $customer->phone ?? '',
                'industry' => 'other',
                'company_size' => '1-10',
                'address' => '',
                'city' => '',
                'province' => '',
                'postal_code' => '',
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the customer password.
     */
    public function updatePassword(Request $request)
    {
        $customer = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
