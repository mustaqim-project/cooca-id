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
    public function index()
    {
        $withdrawals = $this->affiliateService->getWithdrawalsPaginated(15);

        return view('admin.settlements.index', [
            'settlements' => $withdrawals,
        ]);
    }

    public function show(string $id)
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            abort(404, 'Withdrawal not found');
        }

        return view('admin.settlements.show', [
            'settlement' => $withdrawal,
        ]);
    }

    /**
     * Approve the specified withdrawal request.
     */
    public function approve(string $id): JsonResponse
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal request not found'], 404);
        }

        try {
            $this->affiliateService->approveWithdrawal($id, (string) auth()->id());
            return response()->json(['message' => 'Withdrawal approved successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error approving withdrawal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject the specified withdrawal request.
     */
    public function reject(Request $request, string $id): JsonResponse
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
    public function markAsPaid(Request $request, string $id)
    {
        $withdrawal = $this->affiliateService->findWithdrawalById($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'Withdrawal not found'], 404);
        }

        $request->validate([
            'proof_of_payment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_of_payment')) {
            $file = $request->file('proof_of_payment');
            $filename = time() . '_proof.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/proofs'), $filename);
            $proofPath = '/uploads/proofs/' . $filename;
        }

        $this->affiliateService->markWithdrawalAsPaid($id, $proofPath);

        if (!$request->expectsJson()) {
            return back()->with('success', 'Withdrawal marked as paid successfully');
        }

        return response()->json([
            'message' => 'Withdrawal marked as paid',
            'data' => new AffiliateWithdrawalResource($withdrawal->fresh()),
        ]);
    }
}
