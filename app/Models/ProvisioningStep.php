<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ProvisioningStep extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'provisioning_job_id',
        'step_name',
        'step_order',
        'status',
        'output',
        'error_message',
        'retry_count',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function provisioningJob(): BelongsTo
    {
        return $this->belongsTo(ProvisioningJob::class);
    }

    public function markRunning(): void
    {
        $this->update(['status' => 'running', 'started_at' => now()]);
    }

    public function markCompleted(?string $output = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'output' => $output,
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
            'retry_count' => $this->retry_count + 1,
        ]);
    }
}
