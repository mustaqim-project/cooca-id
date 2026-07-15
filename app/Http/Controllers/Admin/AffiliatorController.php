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
        return view('admin.affiliators.create');
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
