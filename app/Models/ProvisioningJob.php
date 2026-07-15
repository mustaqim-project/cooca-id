<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
