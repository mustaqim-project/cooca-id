<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CommissionResource;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class CommissionController extends Controller
{
    public function __construct(
        private readonly AffiliateService $affiliateService
    ) {}

    /**
     * Display listing of commissions.
     */
    public function index(): View
    {
        $affiliator = Auth::guard('affiliator')->user();
        $commissions = \App\Models\AffiliateCommission::where('affiliator_id', $affiliator->getKey())->paginate(15);

        return view('affiliator.commissions.index', [
            'commissions' => \Illuminate\Http\Resources\Json\JsonResource::collection($commissions),
        ]);
    }

    public function stats(): Illuminate\View\View
    {
        $affiliator = Auth::guard('affiliator')->user();
        
        return view('affiliator.commissions.stats', [
            'total_commission' => $this->affiliateService->getTotalCommission($affiliator),
            'cleared_commission' => $this->affiliateService->getTotalCommission($affiliator, 'cleared'),
            'pending_commission' => $this->affiliateService->getTotalCommission($affiliator, 'pending'),
            'breakdown' => $this->affiliateService->getCommissionBreakdown($affiliator),
        ]);
    }

    public function export()
    {
        $affiliator = Auth::guard('affiliator')->user();
        $commissions = \App\Models\AffiliateCommission::where('affiliator_id', $affiliator->getKey())
            ->with(['transaction', 'customer'])
            ->get();

        $csvFileName = 'commissions_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $handle = fopen('php://output', 'w');
        
        return response()->stream(function() use ($handle, $commissions) {
            fputcsv($handle, ['ID', 'Date', 'Transaction ID', 'Customer', 'Level', 'Gross Amount', 'Percent', 'Amount', 'Status']);
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
                    $commission->status
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

}
