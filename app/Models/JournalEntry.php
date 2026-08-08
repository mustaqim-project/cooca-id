<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference',
        'description',
        'journal_id',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(JournalItem::class, 'journal', 'id');
    }
}
