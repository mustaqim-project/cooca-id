<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\Affiliator as AffiliatorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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
        $affiliator = Auth::user();

        return view('affiliator.profile.edit', [
            'user' => [
                'id' => $affiliator->id,
                'name' => $affiliator->name,
                'email' => $affiliator->email,
                'phone' => null,
                'google_id' => $affiliator->google_id,
            ],
            'bank_account' => [
                'bank_name' => $affiliator->bank_name,
                'account_number' => $affiliator->bank_account,
                'account_holder' => $affiliator->name,
            ],
        ]);
    }

    /**
     * Update the affiliator profile.
     */
    public function update(Request $request)
    {
        $affiliator = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:affiliators,email,' . $affiliator->id,
        ]);

        $affiliator->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the affiliator bank account for withdrawals.
     */
    public function updateBankAccount(Request $request)
    {
        $affiliator = Auth::user();

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'nullable|string|max:255',
        ]);

        $affiliator->update([
            'bank_name' => $validated['bank_name'],
            'bank_account' => $validated['account_number'],
        ]);

        return back()->with('success', 'Bank account updated successfully.');
    }

    /**
     * Update the affiliator password.
     */
    public function updatePassword(Request $request)
    {
        $affiliator = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $affiliator->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $affiliator->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
