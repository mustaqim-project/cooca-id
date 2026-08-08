<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class GenerateWeeklyReportsCommand extends Command
{
    protected $signature = 'reports:generate-weekly';
    protected $description = 'Generate weekly business intelligence reports';

    public function handle(): int
    {
        $startOfWeek = now()->subWeek()->startOfWeek();
        $endOfWeek = now()->subWeek()->endOfWeek();

        $newCustomersCount = Customer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $newSubscriptionsCount = Subscription::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $totalRevenue = Invoice::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $this->info("Weekly Report ({$startOfWeek->toDateString()} to {$endOfWeek->toDateString()}):");
        $this->info("- New Customers: {$newCustomersCount}");
        $this->info("- New Subscriptions: {$newSubscriptionsCount}");
        $this->info("- Total Revenue: Rp " . number_format((float)$totalRevenue, 0, ',', '.'));

        Log::info('Weekly business report generated', [
            'period' => "{$startOfWeek->toDateString()} - {$endOfWeek->toDateString()}",
            'new_customers' => $newCustomersCount,
            'new_subscriptions' => $newSubscriptionsCount,
            'total_revenue' => $totalRevenue,
        ]);

        return self::SUCCESS;
    }
}
