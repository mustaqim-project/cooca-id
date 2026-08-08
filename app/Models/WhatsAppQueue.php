<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppQueue extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_queues';
    protected $fillable = ['phone', 'message', 'status', 'session_id', 'media_url'];
}
