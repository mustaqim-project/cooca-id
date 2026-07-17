<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommissionManagementController extends Controller
{
    /**
     * Display a listing of commissions.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'available', 'requested', 'cleared', 'cancelled', 'voided'])],
            'affiliator_email' => ['nullable', 'email'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort_by' => ['nullable', Rule::in(['created_at', 'available_at', 'amount'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $query = AffiliateCommission::with(['affiliateCode.affiliator.user', 'subscription.customer.user']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['affiliator_email'])) {
            $query->whereHas('affiliateCode.affiliator.user', function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['affiliator_email'] . '%');
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $commissions = $query->paginate(20)->withQueryString();

        return view('admin.commissions.index', compact('commissions', 'filters'));
    }

    /**
     * Display the specified commission.
     */
    public function show(string $id)
    {
        $commission = AffiliateCommission::with([
            'affiliateCode.affiliator.user',
            'subscription.customer.user',
            'statusHistory.actor'
        ])->findOrFail($id);

        return view('admin.commissions.show', compact('commission'));
    }

    /**
     * Get statistics for dashboard.
     */
    public function stats()
    {
        $stats = [
            'total' => AffiliateCommission::count(),
            'pending' => AffiliateCommission::where('status', 'pending')->count(),
            'available' => AffiliateCommission::where('status', 'available')->sum('amount'),
            'requested' => AffiliateCommission::where('status', 'requested')->sum('amount'),
            'cleared' => AffiliateCommission::where('status', 'cleared')->sum('amount'),
            'holding_period' => AffiliateCommission::where('status', 'pending')
                ->whereNotNull('available_at')
                ->count(),
        ];

        return response()->json($stats);
    }
}
