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
use Inertia\Inertia;
use Inertia\Response;

final class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService,
        private readonly GenerateLicenseAction $generateLicenseAction
    ) {}

    /**
     * Display listing of licenses.
     */
    public function index(): Response
    {
        $licenses = $this->licenseService->paginate(15);

        return Inertia::render('Admin/Licenses/Index', [
            'licenses' => LicenseResource::collection($licenses),
        ]);
    }

    /**
     * Generate new license codes.
     */
    public function generate(GenerateLicenseRequest $request): JsonResponse
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
    public function revoke(string $id, array $data): JsonResponse
    {
        $license = $this->licenseService->findById($id);

        if (!$license) {
            return response()->json(['message' => 'License not found'], 404);
        }

        $this->licenseService->revoke($id, $data['revoked_by'], $data['reason'] ?? null);

        return response()->json([
            'message' => 'License revoked successfully',
            'data' => new LicenseResource($license->fresh()),
        ]);
    }

    /**
     * Activate the specified license.
     */
    public function activate(string $id): JsonResponse
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
}
