<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Support\Facades\Auth;

final class CommissionController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Display listing of all commissions (paginated).
     */
    public function index()
    {
        $affiliator  = Auth::user();
        $commissions = AffiliateCommission::where('referred_by_id', $affiliator->getKey())
            ->with(['transaction', 'customer'])
            ->latest()
            ->paginate(20);

        return view('affiliator.commissions.index', [
            'commissions' => $commissions,
        ]);
    }

    /**
     * Commission statistics page.
     */
    public function stats()
    {
        $affiliator = Auth::user();

        return view('affiliator.commissions.stats', [
            'total_commission'   => $this->affiliateService->getTotalCommission($affiliator),
            'cleared_commission' => $this->affiliateService->getTotalCommission($affiliator, 'cleared'),
            'pending_commission' => $this->affiliateService->getTotalCommission($affiliator, 'pending'),
            'breakdown'          => $this->affiliateService->getCommissionBreakdown($affiliator),
            'level1_total'       => AffiliateCommission::where('referred_by_id', $affiliator->getKey())
                                        ->where('level', 1)->sum('commission_amount'),
            'level2_total'       => AffiliateCommission::where('referred_by_id', $affiliator->getKey())
                                        ->where('level', 2)->sum('commission_amount'),
        ]);
    }

    /**
     * Export commissions to CSV.
     */
    public function export()
    {
        $affiliator  = Auth::user();
        $commissions = AffiliateCommission::where('referred_by_id', $affiliator->getKey())
            ->with(['transaction', 'customer'])
            ->get();

        $csvFileName = 'commissions_' . date('Y-m-d') . '.csv';
        $headers     = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$csvFileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($commissions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Date', 'Transaction ID', 'Customer', 'Level', 'Gross Amount', 'Percent (%)', 'Commission Amount', 'Status']);
            foreach ($commissions as $commission) {
                fputcsv($handle, [
                    $commission->id,
                    $commission->created_at->format('Y-m-d H:i:s'),
                    $commission->transaction?->invoice_number ?? '-',
                    $commission->customer?->name ?? '-',
                    $commission->level,
                    $commission->gross_amount,
                    $commission->commission_percent,
                    $commission->commission_amount,
                    $commission->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Show detail for one commission.
     */
    public function show(string $commission)
    {
        $affiliator   = Auth::user();
        $commission   = AffiliateCommission::where('referred_by_id', $affiliator->getKey())
            ->with(['transaction', 'customer'])
            ->findOrFail($commission);

        return view('affiliator.commissions.show', compact('commission'));
    }
}
