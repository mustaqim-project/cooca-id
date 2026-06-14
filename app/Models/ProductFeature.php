<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductFeature extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'icon',
        'order',
        'is_highlight',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_highlight' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHighlights($query)
    {
        return $query->where('is_highlight', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
