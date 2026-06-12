<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ValidateLicenseRequest;
use App\Services\License\LicenseService;
use Illuminate\Http\JsonResponse;

final class LicenseValidationController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService
    ) {}

    /**
     * Validate license for ERP client.
     * Triple-check: domain + license_code + token_code
     */
    public function validate(ValidateLicenseRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->licenseService->validateLicense(
            $data['domain'],
            $data['license_code'],
            $data['token_code']
        );

        if ($result['valid']) {
            return response()->json([
                'valid' => true,
                'status' => $result['license']->status,
                'expires_at' => $result['license']->expires_at?->toIso8601String(),
                'customer' => [
                    'id' => $result['license']->customer->id,
                    'business_name' => $result['license']->customer->business_name,
                ],
                'product' => [
                    'id' => $result['license']->product->id,
                    'name' => $result['license']->product->name,
                ],
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => $result['message'] ?? 'Invalid license',
        ], 403);
    }

    /**
     * Heartbeat endpoint for active licenses.
     */
    public function heartbeat(string $licenseCode): JsonResponse
    {
        $license = $this->licenseService->findByCode($licenseCode);

        if (!$license || !$license->isActive()) {
            return response()->json([
                'active' => false,
                'message' => 'License not found or inactive',
            ], 403);
        }

        return response()->json([
            'active' => true,
            'status' => $license->status,
            'expires_at' => $license->expires_at?->toIso8601String(),
        ]);
    }
}
