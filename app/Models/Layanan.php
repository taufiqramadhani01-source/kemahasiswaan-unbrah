<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'icon',
        'whatsapp_number',
        'category',
        'fee',
        'content',
        'is_active',
        'order',
    ];
}
