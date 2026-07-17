<?php

declare(strict_types=1);

namespace App\Events\Trial;

use App\Models\ErpRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrialRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly ErpRequest $erpRequest) {}
}
