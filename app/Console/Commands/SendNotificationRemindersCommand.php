<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class SendNotificationRemindersCommand extends Command
{
    protected $signature = 'notifications:send-reminders';
    protected $description = 'Send scheduled notification reminders to users';

    public function handle(): int
    {
        $now = now();

        $unreadNotifications = Notification::whereNull('read_at')
            ->where('created_at', '<=', $now->subHours(24))
            ->where('created_at', '>=', $now->subDays(7))
            ->limit(100)
            ->get();

        $count = $unreadNotifications->count();
        $this->info("Processed {$count} unread notification reminder(s).");

        Log::info('Notification reminders processed', ['count' => $count]);

        return self::SUCCESS;
    }
}
