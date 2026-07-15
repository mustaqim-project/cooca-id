<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiIntegration extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'api_integrations';

    protected $fillable = [
        'provider',
        'name',
        'config',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'encrypted:array', // Automagically encrypts and decrypts array as JSON
    ];
}
