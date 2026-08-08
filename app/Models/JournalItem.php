<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal',
        'account',
        'description',
        'debit',
        'credit',
    ];

    public function accountObj()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account', 'id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal', 'id');
    }
}
