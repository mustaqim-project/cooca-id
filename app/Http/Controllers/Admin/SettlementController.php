<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliateWithdrawalResource;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



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

        return view('admin.settlements.index', [
            'settlements' => AffiliateWithdrawalResource::collection($withdrawals),
        ]);
    }

    public function show(string $id): Response
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            abort(404, 'Withdrawal not found');
        }

        return view('admin.settlements.show', [
            'settlement' => (new AffiliateWithdrawalResource($withdrawal))->resolve(),
        ]);
    }

    /**
     * Approve the specified withdrawal request.
     */
    public function approve(string $id): RedirectResponse|JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $adminId = Auth::guard('admin')->id();
        abort_if($adminId === null, 403);

        $this->affiliateService->approveWithdrawal($id, (string) $adminId);

        if (!request()->expectsJson()) {
            return back()->with('success', 'Withdrawal approved successfully');
        }

        return response()->json([
            'message' => 'Withdrawal approved successfully',
            'data' => new AffiliateWithdrawalResource($withdrawal->fresh()),
        ]);
    }

    /**
     * Reject the specified withdrawal request.
     */
    public function reject(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $reason = (string) $request->input('reason', 'Rejected by admin');

        $adminId = Auth::guard('admin')->id();
        abort_if($adminId === null, 403);

        $this->affiliateService->rejectWithdrawal($id, (string) $adminId, $reason);

        if (!$request->expectsJson()) {
            return back()->with('success', 'Withdrawal rejected');
        }

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
