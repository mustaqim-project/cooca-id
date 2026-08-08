<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class ExpireLicensesCommand extends Command
{
    protected $signature = 'licenses:expire';
    protected $description = 'Expire licenses that have passed their expiration date';

    public function handle(): int
    {
        $now = now();

        $expiredLicenses = License::where('status', License::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->get();

        if ($expiredLicenses->isEmpty()) {
            $this->info('No licenses to expire.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($expiredLicenses as $license) {
            try {
                $license->update([
                    'status' => License::STATUS_EXPIRED,
                ]);
                $count++;

                Log::info('License expired by scheduled command', [
                    'license_id' => $license->id,
                    'license_code' => $license->license_code,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to expire license', [
                    'license_id' => $license->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Expired {$count} license(s).");
        return self::SUCCESS;
    }
}
