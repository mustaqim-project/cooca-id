<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\AuditLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class CleanupAuditLogsCommand extends Command
{
    protected $signature = 'audit:cleanup-old-logs {--days=90 : Retention period in days}';
    protected $description = 'Remove audit logs and activity logs older than retention period';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        try {
            $auditCount = AuditLog::where('created_at', '<', $cutoff)->delete();
            $activityCount = ActivityLog::where('created_at', '<', $cutoff)->delete();

            $this->info("Cleaned up {$auditCount} audit log(s) and {$activityCount} activity log(s) older than {$days} days.");

            Log::info('Audit and activity logs cleanup completed', [
                'audit_logs_deleted' => $auditCount,
                'activity_logs_deleted' => $activityCount,
                'retention_days' => $days,
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log cleanup failed', ['error' => $e->getMessage()]);
            $this->error('Failed to clean up logs: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
