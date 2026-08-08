<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'holder_name',
        'bank_name',
        'account_number',
        'opening_balance',
        'contact_number',
        'bank_address',
        'chart_account_id',
        'payment_name',
        'created_by',
    ];

    public function chartAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_account_id', 'id');
    }
}
