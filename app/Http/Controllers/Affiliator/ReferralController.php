<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\Auth;



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
        $referrals = \App\Models\Customer::where('affiliator_id', $affiliator->getKey())->paginate(15);

        return view('affiliator.referrals.index', [
            'referrals' => CustomerResource::collection($referrals),
            'referral_link' => route('customer.register', ['referral' => $affiliator->referral_code]),
        ]);
    }

    public function stats(): Illuminate\View\View
    {
        return view('affiliator.referrals.Stats');
    }

}
