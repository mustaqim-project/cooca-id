<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CommissionResource;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class CommissionController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Display listing of commissions.
     */
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $commissions = $this->affiliateService->getCommissionsByAffiliator($affiliator->getKey());

        return Inertia::render('Affiliator/Commissions/Index', [
            'commissions' => CommissionResource::collection($commissions),
        ]);
    }
}
