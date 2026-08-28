<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ReferralController extends Controller
{
    public function __construct(
        private readonly AffiliatorRepositoryInterface $affiliatorRepository
    ) {}

    /**
     * Display listing of referrals.
     */
    public function index()
    {
        $affiliator = Auth::user();
        $referrals = \App\Models\Customer::where('affiliator_id', $affiliator->getKey())->paginate(15);

        return view('affiliator.referrals.index', [
            'referrals' => CustomerResource::collection($referrals),
            'referral_link' => route('register', ['ref' => $affiliator->referral_code]),
        ]);
    }

    public function stats()
    {
        return view('affiliator.referrals.stats');
    }

    /**
     * Display a single referral (customer) details.
     */
    public function show(\App\Models\Customer $customer)
    {
        Gate::authorize('view', $customer);

        return view('affiliator.referrals.show', ['referral' => $customer]);
    }
}
