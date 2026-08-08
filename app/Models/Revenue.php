<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'account_id',
        'customer_id',
        'category_id',
        'payment_method',
        'reference',
        'description',
        'created_by',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'account_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ChartOfAccount::class, 'category_id', 'id');
    }
}
