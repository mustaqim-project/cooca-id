<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends Controller
{
    /**
     * Display affiliator dashboard.
     */
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();

        return Inertia::render('Affiliator/Dashboard/Index', [
            'stats' => [
                'total_referrals' => 0,
                'total_commissions' => 0,
                'pending_commissions' => 0,
                'balance' => $affiliator->balance ?? 0,
                'total_withdrawals' => 0,
            ],
        ]);
    }
}
