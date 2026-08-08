<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class DeactivateVouchersCommand extends Command
{
    protected $signature = 'vouchers:deactivate';
    protected $description = 'Deactivate vouchers that have passed their validity period or reached max usages';

    public function handle(): int
    {
        $now = now();

        $vouchersToDeactivate = Voucher::where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->whereNotNull('valid_until')->where('valid_until', '<', $now);
                })->orWhere(function ($q) {
                    $q->where('usage_limit', '>', 0)->whereColumn('used_count', '>=', 'usage_limit');
                });
            })
            ->get();

        if ($vouchersToDeactivate->isEmpty()) {
            $this->info('No vouchers to deactivate.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($vouchersToDeactivate as $voucher) {
            try {
                $voucher->update(['is_active' => false]);
                $count++;

                Log::info('Voucher deactivated by scheduled command', [
                    'voucher_id' => $voucher->id,
                    'code' => $voucher->code,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to deactivate voucher', [
                    'voucher_id' => $voucher->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Deactivated {$count} voucher(s).");
        return self::SUCCESS;
    }
}
