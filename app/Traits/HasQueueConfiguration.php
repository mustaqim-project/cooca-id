<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait for queued Mail and Notification classes.
 *
 * Adds exponential backoff, retry logic, and timeout configuration
 * to prevent server overload and ensure reliable delivery.
 */
trait HasQueueConfiguration
{
    /**
     * The number of times the job may be attempted.
     * After 5 failed attempts, the job is moved to failed_jobs table.
     */
    public int $tries = 5;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 30;

    /**
     * Exponential backoff delay in seconds between retries.
     *
     * Attempt 1: wait  30s
     * Attempt 2: wait  60s
     * Attempt 3: wait 120s
     * Attempt 4: wait 240s
     * Attempt 5: wait 480s
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [30, 60, 120, 240, 480];
    }
}
