<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealTask extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }
}
