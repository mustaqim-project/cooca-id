<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    /**
     * Display customer dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Customer/Dashboard', [
            'stats' => [
                'active_licenses' => 0,
                'total_subscriptions' => 0,
                'pending_invoices' => 0,
                'total_spent' => 0,
            ],
        ]);
    }
}
