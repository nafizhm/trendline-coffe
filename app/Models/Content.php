<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'published_at',
        'type',
        'content',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
