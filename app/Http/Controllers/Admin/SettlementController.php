<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliateWithdrawalResource;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

final class SettlementController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Display listing of withdrawal requests.
     */
    public function index(): Response
    {
        $withdrawals = $this->affiliateService->getWithdrawalsPaginated(15);

        return Inertia::render('Admin/Settlements/Index', [
            'withdrawals' => AffiliateWithdrawalResource::collection($withdrawals),
        ]);
    }

    /**
     * Approve the specified withdrawal request.
     */
    public function approve(string $id, string $adminId): JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $this->affiliateService->approveWithdrawal($id, $adminId);

        return response()->json([
            'message' => 'Withdrawal approved successfully',
            'data' => new AffiliateWithdrawalResource($withdrawal->fresh()),
        ]);
    }

    /**
     * Reject the specified withdrawal request.
     */
    public function reject(string $id, string $adminId, string $reason): JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $this->affiliateService->rejectWithdrawal($id, $adminId, $reason);

        return response()->json([
            'message' => 'Withdrawal rejected',
            'data' => new AffiliateWithdrawalResource($withdrawal->fresh()),
        ]);
    }

    /**
     * Mark withdrawal as paid.
     */
    public function markAsPaid(string $id): JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $this->affiliateService->markWithdrawalAsPaid($id);

        return response()->json([
            'message' => 'Withdrawal marked as paid',
            'data' => new AffiliateWithdrawalResource($withdrawal->fresh()),
        ]);
    }
}
