<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliatorResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

final class AffiliatorController extends Controller
{
    public function __construct(
        private readonly AffiliatorRepositoryInterface $affiliatorRepository
    ) {}

    /**
     * Display listing of affiliators.
     */
    public function index()
    {
        $affiliators = $this->affiliatorRepository->paginate(15);

        return view('admin.affiliators.index', [
            'affiliators' => AffiliatorResource::collection($affiliators),
        ]);
    }

    /**
     * Show the form for creating a new affiliator.
     */
    public function create()
    {
        $allAffiliators = \App\Models\Affiliator::orderBy('name')->get();
        return view('admin.affiliators.create', compact('allAffiliators'));
    }

    /**
     * Store a newly created affiliator.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:affiliators',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:50',
            'referral_code' => 'nullable|string|max:50|unique:affiliators,referral_code',
            'parent_affiliator_id' => 'nullable|uuid|exists:affiliators,id',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $this->affiliatorRepository->create($validated);

        return redirect()->route('admin.affiliators.index')
            ->with('success', 'Affiliator created successfully.');
    }

    /**
     * Display the specified affiliator.
     */
    public function show(string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            abort(404, 'Affiliator not found');
        }

        return view('admin.affiliators.show', [
            'affiliator' => new AffiliatorResource($affiliator),
            'downlines' => AffiliatorResource::collection($affiliator->downlines),
        ]);
    }

    /**
     * Suspend the specified affiliator.
     */
    public function suspend(Request $request, string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return redirect()->route('admin.affiliators.index')->with('error', 'Affiliator not found');
        }

        $validated = $request->validate([
            'suspension_reason_type' => 'required|string',
            'suspension_reason_notes' => 'nullable|string',
        ]);

        $this->affiliatorRepository->update($id, [
            'status' => 'suspended',
            'suspension_reason_type' => $validated['suspension_reason_type'],
            'suspension_reason_notes' => $validated['suspension_reason_notes'] ?? null,
        ]);

        return redirect()->route('admin.affiliators.show', $id)->with('success', 'Affiliator suspended successfully');
    }

    /**
     * Show the form for editing the specified affiliator.
     */
    public function edit(string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            abort(404, 'Affiliator not found');
        }

        $allAffiliators = \App\Models\Affiliator::where('id', '!=', $id)->orderBy('name')->get();

        return view('admin.affiliators.edit', [
            'affiliator' => new AffiliatorResource($affiliator),
            'allAffiliators' => $allAffiliators,
        ]);
    }

    /**
     * Update the specified affiliator.
     */
    public function update(Request $request, string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return redirect()->route('admin.affiliators.index')->with('error', 'Affiliator not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliators,email,' . $id,
            'phone' => 'nullable|string|max:50',
            'referral_code' => 'nullable|string|max:50|unique:affiliators,referral_code,' . $id,
            'parent_affiliator_id' => 'nullable|uuid|exists:affiliators,id',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'balance' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,suspended',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $this->affiliatorRepository->update($id, $validated);

        return redirect()->route('admin.affiliators.index')->with('success', 'Affiliator updated successfully');
    }

    /**
     * Remove the specified affiliator.
     */
    public function destroy(string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return redirect()->route('admin.affiliators.index')->with('error', 'Affiliator not found');
        }

        $this->affiliatorRepository->delete($id);

        return redirect()->route('admin.affiliators.index')->with('success', 'Affiliator deleted successfully');
    }

    /**
     * Reactivate the specified affiliator.
     */
    public function reactivate(string $id)
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return redirect()->route('admin.affiliators.index')->with('error', 'Affiliator not found');
        }

        $this->affiliatorRepository->update($id, [
            'status' => 'active',
            'suspension_reason_type' => null,
            'suspension_reason_notes' => null,
            'appeal_reason' => null,
            'appeal_proof_path' => null,
            'appealed_at' => null,
        ]);

        return redirect()->route('admin.affiliators.show', $id)->with('success', 'Affiliator reactivated successfully');
    }
}
