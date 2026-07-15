<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseAppeal;
use Illuminate\Http\Request;

class LicenseAppealController extends Controller
{
    public function store(Request $request, string $licenseId)
    {
        $license = License::where('id', $licenseId)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();

        if ($license->status !== 'revoked') {
            return redirect()->back()->with('error', 'License is not revoked.');
        }

        // Check if there is already a pending appeal
        if ($license->latestAppeal && $license->latestAppeal->status === 'pending') {
            return redirect()->back()->with('error', 'You already have a pending appeal.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
            'proof' => 'nullable|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('appeals', 'public_uploads');
        }

        LicenseAppeal::create([
            'license_id' => $license->id,
            'reason' => $validated['reason'],
            'proof_path' => $proofPath,
        ]);

        return redirect()->back()->with('success', 'Appeal submitted successfully.');
    }
}
