<?php
declare(strict_types=1);

namespace App\Events\User;

use App\Models\Affiliator;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AffiliatorRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Affiliator $affiliator,
    ) {}
}
