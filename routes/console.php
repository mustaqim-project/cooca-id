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

// Subscription expiry commands
Schedule::command('subscriptions:expire')->dailyAt('00:05');
Schedule::command('subscriptions:remind --days=7')->dailyAt('09:00');
Schedule::command('subscriptions:remind --days=3')->dailyAt('09:00');

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

// Custom Artisan Commands (to be created)
Artisan::command('licenses:expire', function () {
    $this->info('Expiring licenses...');
})->purpose('Expire licenses that have passed their expiration date');

Artisan::command('vouchers:deactivate', function () {
    $this->info('Deactivating expired vouchers...');
})->purpose('Deactivate vouchers that have passed their validity period');

Artisan::command('notifications:send-reminders', function () {
    $this->info('Sending notification reminders...');
})->purpose('Send scheduled notification reminders to users');

Artisan::command('audit:cleanup-old-logs', function () {
    $this->info('Cleaning up old audit logs...');
})->purpose('Remove audit logs older than retention period');

Artisan::command('reports:generate-weekly', function () {
    $this->info('Generating weekly reports...');
})->purpose('Generate weekly business intelligence reports');

Artisan::command('affiliates:calculate-monthly', function () {
    $this->info('Calculating monthly affiliate statistics...');
})->purpose('Calculate and store monthly affiliate performance metrics');

