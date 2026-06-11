<?php

declare(strict_types=1);

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

// Daily Tasks
Schedule::command('licenses:expire')->dailyAt('01:00')->name('Expire expired licenses');
Schedule::command('subscriptions:check-expiry')->dailyAt('02:00')->name('Check subscription expiry');
Schedule::command('vouchers:deactivate')->dailyAt('03:00')->name('Deactivate expired vouchers');
Schedule::command('notifications:send-reminders')->dailyAt('08:00')->name('Send notification reminders');

// Hourly Tasks
Schedule::command('cache:prune-stale-tags')->hourly()->name('Prune stale cache tags');

// Weekly Tasks
Schedule::command('audit:cleanup-old-logs')->weekly()->name('Cleanup old audit logs');
Schedule::command('reports:generate-weekly')->weeklyOn(1, '09:00')->name('Generate weekly reports');

// Monthly Tasks
Schedule::command('affiliates:calculate-monthly')->monthlyOn(1, '00:00')->name('Calculate monthly affiliate stats');
Schedule::command('database:backup')->monthlyOn(1, '04:00')->name('Monthly database backup');

// Custom Artisan Commands (to be created)
Artisan::command('licenses:expire', function () {
    $this->info('Expiring licenses...');
    // Logic to expire licenses will be in a command class
})->purpose('Expire licenses that have passed their expiration date');

Artisan::command('subscriptions:check-expiry', function () {
    $this->info('Checking subscription expiry...');
    // Logic to check and notify about expiring subscriptions
})->purpose('Check subscriptions nearing expiry and send notifications');

Artisan::command('vouchers:deactivate', function () {
    $this->info('Deactivating expired vouchers...');
    // Logic to deactivate expired vouchers
})->purpose('Deactivate vouchers that have passed their validity period');

Artisan::command('notifications:send-reminders', function () {
    $this->info('Sending notification reminders...');
    // Logic to send reminder notifications
})->purpose('Send scheduled notification reminders to users');

Artisan::command('audit:cleanup-old-logs', function () {
    $this->info('Cleaning up old audit logs...');
    // Logic to cleanup old audit logs (older than 90 days)
})->purpose('Remove audit logs older than retention period');

Artisan::command('reports:generate-weekly', function () {
    $this->info('Generating weekly reports...');
    // Logic to generate weekly business reports
})->purpose('Generate weekly business intelligence reports');

Artisan::command('affiliates:calculate-monthly', function () {
    $this->info('Calculating monthly affiliate statistics...');
    // Logic to calculate monthly affiliate performance
})->purpose('Calculate and store monthly affiliate performance metrics');

Artisan::command('database:backup', function () {
    $this->info('Creating database backup...');
    // Logic to create database backup
})->purpose('Create monthly database backup');
