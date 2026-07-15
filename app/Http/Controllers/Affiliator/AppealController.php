<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppealController extends Controller
{
    /**
     * Show the appeal submission form.
     */
    public function index()
    {
        $affiliator = auth()->guard('affiliator')->user();
        
        // If they are not suspended, they shouldn't be here
        if ($affiliator->status !== 'suspended') {
            return redirect()->route('affiliator.dashboard');
        }

        return view('affiliate.appeal', [
            'affiliator' => $affiliator
        ]);
    }

    /**
     * Submit the appeal.
     */
    public function store(Request $request)
    {
        $affiliator = auth()->guard('affiliator')->user();

        if ($affiliator->status !== 'suspended') {
            return redirect()->route('affiliator.dashboard');
        }

        $validated = $request->validate([
            'appeal_reason' => 'required|string',
            'appeal_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $proofPath = $affiliator->appeal_proof_path;

        if ($request->hasFile('appeal_proof')) {
            $file = $request->file('appeal_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // As requested, store in public folder
            $file->move(public_path('uploads/appeals'), $filename);
            $proofPath = 'uploads/appeals/' . $filename;
        }

        $affiliator->update([
            'appeal_reason' => $validated['appeal_reason'],
            'appeal_proof_path' => $proofPath,
            'appealed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Your appeal has been submitted successfully and is awaiting review.');
    }
}
