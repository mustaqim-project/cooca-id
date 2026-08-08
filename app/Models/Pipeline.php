<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function stages()
    {
        return $this->hasMany(Stage::class, 'pipeline_id')->orderBy('order');
    }
}
