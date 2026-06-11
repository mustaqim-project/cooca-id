<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliator\RequestWithdrawalRequest;
use App\Services\AffiliateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class WithdrawalController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Request a new withdrawal.
     */
    public function store(RequestWithdrawalRequest $request): JsonResponse
    {
        $affiliator = Auth::guard('affiliator')->user();
        $data = $request->validated();

        $withdrawal = $this->affiliateService->requestWithdrawal(
            $affiliator->getKey(),
            $data['amount'],
            $data['withdrawal_method'],
            $data['account_number'],
            $data['account_name']
        );

        return response()->json([
            'message' => 'Withdrawal request submitted successfully',
            'data' => $withdrawal,
        ], 201);
    }
}
