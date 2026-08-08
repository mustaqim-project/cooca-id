<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use App\Models\BlockedIp;
use Illuminate\Support\Facades\Log;

class BlockIpOnLockout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Lockout $event): void
    {
        $ip = $event->request->ip();

        if ($ip) {
            $blocked = BlockedIp::firstOrCreate(
                ['ip_address' => $ip],
                ['reason' => 'Terlalu banyak percobaan login gagal (Brute Force)']
            );

            if ($blocked->wasRecentlyCreated) {
                Log::warning("IP {$ip} has been blocked due to brute force login attempts.");
            }
        }
    }
}
