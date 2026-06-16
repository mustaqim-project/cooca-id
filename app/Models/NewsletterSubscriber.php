<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NewsletterSubscriber extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'newsletter_subscribers';
    protected $fillable = ['email'];
    protected $keyType = 'string';
    public $incrementing = false;
}
