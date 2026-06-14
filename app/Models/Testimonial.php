<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'position',
        'company',
        'content',
        'avatar',
        'rating',
        'product_type',
        'is_featured',
        'is_active',
        'order',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeProductType($query, string $type)
    {
        return $query->where('product_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
