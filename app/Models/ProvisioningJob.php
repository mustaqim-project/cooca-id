<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProvisioningJob extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'erp_request_id',
        'tenant_uuid',
        'db_name',
        'db_user',
        'db_password',
        'subdomain',
        'current_step',
        'status',
        'attempts',
        'error_message',
    ];

    public function erpRequest(): BelongsTo
    {
        return $this->belongsTo(ErpRequest::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ProvisioningStep::class)->orderBy('step_order');
    }

    public function currentStepModel(): ?ProvisioningStep
    {
        return $this->steps()->where('status', 'running')->first();
    }

    public function nextPendingStep(): ?ProvisioningStep
    {
        return $this->steps()->where('status', 'pending')->first();
    }

    public function allStepsCompleted(): bool
    {
        return $this->steps()->where('status', '!=', 'completed')->doesntExist();
    }
}
