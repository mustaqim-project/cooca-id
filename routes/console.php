<?php declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes - Scheduled Tasks
|--------------------------------------------------------------------------
|
| Define scheduled tasks for the application here.
|
*/

// Process queue for shared hosting (since there is no Supervisor)
Schedule::command('queue:work --stop-when-empty --max-time=50')->everyMinute()->withoutOverlapping();


// Subscription expiry commands
Schedule::command('subscriptions:expire')->dailyAt('00:05');
Schedule::command('subscriptions:remind --days=7')->dailyAt('09:00');
Schedule::command('subscriptions:remind --days=3')->dailyAt('09:00');

// Affiliate
Schedule::command('affiliate:clear-commissions')->dailyAt('00:00');

// Daily Tasks
Schedule::command('licenses:expire')->dailyAt('01:00')->name('Expire expired licenses');
Schedule::command('vouchers:deactivate')->dailyAt('03:00')->name('Deactivate expired vouchers');
Schedule::command('notifications:send-reminders')->dailyAt('08:00')->name('Send notification reminders');

// Hourly Tasks
Schedule::command('cache:prune-stale-tags')->hourly()->name('Prune stale cache tags');

// Weekly Tasks
Schedule::command('audit:cleanup-old-logs')->weekly()->name('Cleanup old audit logs');
Schedule::command('reports:generate-weekly')->weeklyOn(1, '09:00')->name('Generate weekly reports');

// Monthly Tasks
Schedule::command('affiliates:calculate-monthly')->monthlyOn(1, '00:00')->name('Calculate monthly affiliate stats');
Schedule::command('backup:clean')->dailyAt('01:00');
Schedule::command('backup:run')->dailyAt('01:30');

