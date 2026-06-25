<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliator\RequestWithdrawalRequest;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

final class WithdrawalController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Request a new withdrawal.
     */
    public function store(RequestWithdrawalRequest $request): RedirectResponse
    {
        $affiliator = Auth::guard('affiliator')->user();
        $data = $request->validated();

        $withdrawal = $this->affiliateService->requestWithdrawal(
            $affiliator->getKey(),
            (float) $data['amount'],
            $data['withdrawal_method'],
            $data['account_number'],
            $data['account_name']
        );

        return redirect()->route('affiliator.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully');
    }

    /**
     * Display listing of withdrawals.
     */
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $withdrawals = $this->affiliateService->getWithdrawals($affiliator->getKey());

        return Inertia::render('Affiliator/Withdrawals/Index', [
            'withdrawals' => $withdrawals,
            'balance' => $this->affiliateService->getAvailableBalance($affiliator->getKey()),
            'minimumWithdrawal' => (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000)),
        ]);
    }

    /**
     * Show the form for creating a new withdrawal.
     */
    public function create(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $balance = $this->affiliateService->getAvailableBalance($affiliator->getKey());

        return Inertia::render('Affiliator/Withdrawals/Create', [
            'availableBalance' => $balance,
            'withdrawalFee' => [
                'bank' => (float) Setting::get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 2500)),
                'ewallet' => (float) Setting::get('affiliate.withdrawal_fee_ewallet', config('affiliate.withdrawal_fee_ewallet', 1000)),
            ],
            'minimumWithdrawal' => (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000)),
            'bankAccount' => [
                'bank_name' => $affiliator->bank_name ?? '',
                'account_number' => $affiliator->bank_account ?? '',
                'account_holder' => $affiliator->name ?? '',
                'type' => 'bank',
            ],
        ]);
    }

    /**
     * Display the specified withdrawal.
     */
    public function show(string $id): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $withdrawal = $this->affiliateService->getWithdrawalById($id, $affiliator->getKey());

        if (!$withdrawal) {
            abort(404, 'Withdrawal not found');
        }

        return Inertia::render('Affiliator/Withdrawals/Show', [
            'withdrawal' => $withdrawal,
        ]);
    }

    /**
     * Display withdrawal history.
     */
    public function history(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $withdrawals = $this->affiliateService->getWithdrawalHistory($affiliator->getKey());

        return Inertia::render('Affiliator/Withdrawals/History', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
