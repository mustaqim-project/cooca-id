<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AffiliatorApiController extends Controller
{
    // GET /api/v1/affiliator/dashboard
    public function dashboard(Request $request): JsonResponse
    {
        $affiliator = $request->user();
        return response()->json([
            'data' => [
                'balance' => $affiliator->balance,
                'total_referrals' => $affiliator->customers()->count(),
                'total_commissions' => $affiliator->commissions()->sum('amount'),
                'pending_commissions' => $affiliator->commissions()->where('status', 'pending')->sum('amount'),
                'total_downlines' => $affiliator->children()->count(),
            ],
        ]);
    }

    // GET /api/v1/affiliator/referrals
    public function referrals(Request $request): JsonResponse
    {
        $referrals = $request->user()->customers()
            ->select('id', 'name', 'email', 'created_at')
            ->latest()
            ->paginate(15);

        return response()->json($referrals);
    }

    // GET /api/v1/affiliator/commissions
    public function commissions(Request $request): JsonResponse
    {
        $commissions = $request->user()->commissions()
            ->latest()
            ->paginate(15);

        return response()->json($commissions);
    }

    // GET /api/v1/affiliator/withdrawals
    public function withdrawals(Request $request): JsonResponse
    {
        $withdrawals = $request->user()->withdrawals()
            ->latest()
            ->paginate(15);

        return response()->json($withdrawals);
    }

    // POST /api/v1/affiliator/withdrawals
    public function requestWithdrawal(Request $request): JsonResponse
    {
        $affiliator = $request->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:50000',
        ]);

        if ($validated['amount'] > $affiliator->balance) {
            return response()->json(['message' => 'Insufficient balance.'], 422);
        }

        $withdrawal = $affiliator->withdrawals()->create([
            'amount' => $validated['amount'],
            'bank_name' => $affiliator->bank_name,
            'bank_account' => $affiliator->bank_account,
            'status' => 'pending',
        ]);

        return response()->json(['data' => $withdrawal], 201);
    }

    // GET /api/v1/affiliator/downlines
    public function downlines(Request $request): JsonResponse
    {
        $downlines = $request->user()->children()
            ->select('id', 'name', 'email', 'referral_code', 'created_at')
            ->withCount('customers')
            ->latest()
            ->paginate(15);

        return response()->json($downlines);
    }
}
