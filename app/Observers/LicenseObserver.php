<?php
declare(strict_types=1);

namespace App\Observers;

use App\Events\License\LicenseGenerated;
use App\Events\License\LicenseRevoked;
use App\Models\License;

final class LicenseObserver
{
    public function created(License $license): void
    {
        if ($license->status === 'active') {
            event(new LicenseGenerated($license));
        }
    }

    public function updated(License $license): void
    {
        if ($license->isDirty('status')) {
            if ($license->status === 'active' && $license->wasChanged('status')) {
                event(new LicenseGenerated($license));
            }
            if ($license->status === 'revoked' && $license->wasChanged('status')) {
                event(new LicenseRevoked($license));
            }
        }
    }
}
