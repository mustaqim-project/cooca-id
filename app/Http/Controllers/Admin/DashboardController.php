<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_customers' => 0,
                'total_affiliators' => 0,
                'total_licenses' => 0,
                'total_revenue' => 0,
                'pending_transactions' => 0,
                'pending_withdrawals' => 0,
            ],
        ]);
    }
}
