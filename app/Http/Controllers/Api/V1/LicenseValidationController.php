<?php declare(strict_types=1);

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

    public function validate(ValidateLicenseRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Use the activateErpLicense which handles binding domain if missing, and generating proper payload
        $result = $this->licenseService->activateErpLicense($data);

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => 'License berhasil divalidasi.',
                'data' => $result['data'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Invalid license',
        ], 403);
    }

    /**
     * Heartbeat endpoint for active licenses.
     */
    public function heartbeat(string $licenseCode)
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
