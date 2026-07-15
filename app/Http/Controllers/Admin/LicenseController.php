<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\License\GenerateLicense\GenerateLicenseAction;
use App\DTOs\LicenseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateLicenseRequest;
use App\Http\Resources\Admin\LicenseResource;
use App\Services\License\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\LicenseAppeal;
use Illuminate\Http\Request;

final class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService,
        private readonly GenerateLicenseAction $generateLicenseAction
    ) {}

    /**
     * Display listing of licenses.
     */
    public function index()
    {
        $licenses = $this->licenseService->paginate(15);

        return view('admin.licenses.index', [
            'licenses' => LicenseResource::collection($licenses),
        ]);
    }

    /**
     * Display the specified license.
     */
    public function show(string $id)
    {
        $license = $this->licenseService->findById($id);

        if (!$license) {
            abort(404, 'License not found');
        }

        return view('admin.licenses.show', [
            'license' => $license,
        ]);
    }

    /**
     * Generate new license codes.
     */
    public function generate(GenerateLicenseRequest $request)
    {
        $data = $request->validated();
        
        $licenseData = LicenseData::from([
            'customer_id' => $data['customer_id'],
            'product_id' => $data['product_id'],
            'subscription_plan_id' => $data['subscription_plan_id'],
            'domain' => $data['domain'],
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        $license = ($this->generateLicenseAction)($licenseData);

        return response()->json([
            'message' => 'License generated successfully',
            'data' => new LicenseResource($license),
        ], 201);
    }

    /**
     * Revoke the specified license.
     */
    public function revoke(\Illuminate\Http\Request $request, string $id)
    {
        $license = $this->licenseService->findById($id);

        if (!$license) {
            return response()->json(['message' => 'License not found'], 404);
        }

        $data = $request->validate([
            'category' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        $this->licenseService->revoke($id, auth('admin')->id(), $data['reason'] ?? null, $data['category']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'License revoked successfully',
                'data' => new LicenseResource($license->fresh()),
            ]);
        }

        return redirect()->back()->with('success', 'License revoked successfully');
    }

    /**
     * Activate the specified license.
     */
    public function activate(string $id)
    {
        $license = $this->licenseService->findById($id);

        if (!$license) {
            return response()->json(['message' => 'License not found'], 404);
        }

        $this->licenseService->activate($id);

        return response()->json([
            'message' => 'License activated successfully',
            'data' => new LicenseResource($license->fresh()),
        ]);
    }

    public function approveAppeal(Request $request, string $licenseId, string $appealId)
    {
        $license = $this->licenseService->findById($licenseId);
        if (!$license) abort(404);

        $appeal = LicenseAppeal::findOrFail($appealId);
        
        $appeal->update([
            'status' => 'approved',
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
        ]);

        $this->licenseService->activate($licenseId);

        return redirect()->back()->with('success', 'Appeal approved and license reactivated successfully.');
    }

    public function rejectAppeal(Request $request, string $licenseId, string $appealId)
    {
        $license = $this->licenseService->findById($licenseId);
        if (!$license) abort(404);

        $appeal = LicenseAppeal::findOrFail($appealId);
        
        $appeal->update([
            'status' => 'rejected',
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Appeal rejected.');
    }
}
