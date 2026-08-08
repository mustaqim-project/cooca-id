<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AffiliateCommission;
use App\Models\Affiliator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class CalculateMonthlyAffiliatesCommand extends Command
{
    protected $signature = 'affiliates:calculate-monthly';
    protected $description = 'Calculate and store monthly affiliate performance metrics';

    public function handle(): int
    {
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();

        $totalCommissions = AffiliateCommission::where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $activeAffiliatorsCount = Affiliator::where('status', 'active')->count();

        $this->info("Monthly Affiliate Summary ({$startOfMonth->format('F Y')}):");
        $this->info("- Active Affiliators: {$activeAffiliatorsCount}");
        $this->info("- Total Commissions Paid: Rp " . number_format((float)$totalCommissions, 0, ',', '.'));

        Log::info('Monthly affiliate statistics calculated', [
            'month' => $startOfMonth->format('Y-m'),
            'active_affiliators' => $activeAffiliatorsCount,
            'total_commissions' => $totalCommissions,
        ]);

        return self::SUCCESS;
    }
}
