<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'sub_type',
        'is_enabled',
        'description',
        'parent',
        'created_by',
    ];

    public function typeAccount()
    {
        return $this->belongsTo(ChartOfAccountType::class, 'type', 'id');
    }

    public function subTypeAccount()
    {
        return $this->belongsTo(ChartOfAccountSubType::class, 'sub_type', 'id');
    }

    public function journalItems()
    {
        return $this->hasMany(JournalItem::class, 'account', 'id');
    }
}
