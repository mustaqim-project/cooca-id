<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\Affiliator as AffiliatorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

/**
 * Affiliator Profile Controller
 * 
 * Manages affiliator profile and bank account settings.
 */
class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        $affiliator = Auth::guard('affiliator')->user();

        return Inertia::render('Affiliator/Profile/Edit', [
            'affiliator' => $affiliator,
        ]);
    }

    /**
     * Update the affiliator profile.
     */
    public function update(Request $request)
    {
        $affiliator = Auth::guard('affiliator')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:affiliators,email,' . $affiliator->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|array',
            'social_media.facebook' => 'nullable|url|max:255',
            'social_media.twitter' => 'nullable|url|max:255',
            'social_media.instagram' => 'nullable|url|max:255',
            'social_media.linkedin' => 'nullable|url|max:255',
        ]);

        $affiliator->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the affiliator bank account for withdrawals.
     */
    public function updateBankAccount(Request $request)
    {
        $affiliator = Auth::guard('affiliator')->user();

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'withdrawal_method' => 'required|in:bank,ewallet',
            'ewallet_provider' => 'nullable|string|max:100',
            'ewallet_number' => 'nullable|string|max:50',
        ]);

        $affiliator->update([
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'withdrawal_method' => $validated['withdrawal_method'],
            'ewallet_provider' => $validated['ewallet_provider'] ?? null,
            'ewallet_number' => $validated['ewallet_number'] ?? null,
        ]);

        return back()->with('success', 'Bank account updated successfully.');
    }

    /**
     * Update the affiliator password.
     */
    public function updatePassword(Request $request)
    {
        $affiliator = Auth::guard('affiliator')->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $affiliator->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $affiliator->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
