<?php
declare(strict_types=1);

namespace App\Observers;

use App\Events\User\AffiliatorRegistered;
use App\Models\Affiliator;

final class AffiliatorObserver
{
    public function created(Affiliator $affiliator): void
    {
        event(new AffiliatorRegistered($affiliator));
    }
}


