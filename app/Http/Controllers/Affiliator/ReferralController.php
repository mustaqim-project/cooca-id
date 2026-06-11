<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ReferralController extends Controller
{
    public function __construct(
        private readonly AffiliatorRepositoryInterface $affiliatorRepository
    ) {}

    /**
     * Display listing of referrals.
     */
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $referrals = $this->affiliatorRepository->getReferrals($affiliator->getKey());

        return Inertia::render('Affiliator/Referrals/Index', [
            'referrals' => CustomerResource::collection($referrals),
            'referral_link' => route('customer.register', ['referral' => $affiliator->referral_code]),
        ]);
    }
}
