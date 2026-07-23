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
        // NOTE: config is NOT cast here - encryption is handled by custom accessor/mutator below
    ];

    /**
     * Accessor: decrypt and return config as a plain array.
     *
     * The raw value in DB is an encrypted JSON string.
     * We decrypt it and decode from JSON to array.
     */
    public function getConfigAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        // $value comes from $this->attributes['config'] which is the raw encrypted string
        try {
            $decrypted = decrypt($value);
            $decoded = json_decode($decrypted, true);
            return is_array($decoded) ? $decoded : [];
        } catch (\Exception $e) {
            // If value is not encrypted (e.g. fresh model), try parsing as JSON directly
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
    }

    /**
     * Mutator: accept array, encode to JSON, and encrypt before storing.
     */
    public function setConfigAttribute($value): void
    {
        $encoded = json_encode($value ?? []);
        $this->attributes['config'] = encrypt($encoded);
    }
}
