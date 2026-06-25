<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateVoucherRequest;
use App\Http\Resources\Admin\VoucherResource;
use App\Services\Voucher\VoucherService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

final class VoucherController extends Controller
{
    public function __construct(
        private readonly VoucherService $voucherService
    ) {}

    /**
     * Display listing of vouchers.
     */
    public function index(): Response
    {
        $vouchers = $this->voucherService->paginate(15);

        return Inertia::render('Admin/Vouchers/Index', [
            'vouchers' => VoucherResource::collection($vouchers),
        ]);
    }

    /**
     * Store a newly created voucher.
     */
    public function store(CreateVoucherRequest $request): JsonResponse
    {
        $data = $request->validated();
        $voucher = $this->voucherService->create($data);

        return response()->json([
            'message' => 'Voucher created successfully',
            'data' => new VoucherResource($voucher),
        ], 201);
    }

    /**
     * Update the specified voucher.
     */
    public function update(CreateVoucherRequest $request, string $id): JsonResponse
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $this->voucherService->update($id, $request->validated());

        return response()->json([
            'message' => 'Voucher updated successfully',
            'data' => new VoucherResource($voucher->fresh()),
        ]);
    }

    /**
     * Activate the specified voucher.
     */
    public function activate(string $id): JsonResponse
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $this->voucherService->activate($id);

        return response()->json([
            'message' => 'Voucher activated successfully',
            'data' => new VoucherResource($voucher->fresh()),
        ]);
    }

    /**
     * Deactivate the specified voucher.
     */
    public function deactivate(string $id): JsonResponse
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $this->voucherService->deactivate($id);

        return response()->json([
            'message' => 'Voucher deactivated successfully',
            'data' => new VoucherResource($voucher->fresh()),
        ]);
    }
}
