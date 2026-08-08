<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
    
    public function tasks()
    {
        return $this->hasMany(DealTask::class, 'deal_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
