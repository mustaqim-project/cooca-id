<?php

declare(strict_types=1);

namespace App\Events\Domain;

use App\Models\Domain;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DomainVerified
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Domain $domain) {}
}
