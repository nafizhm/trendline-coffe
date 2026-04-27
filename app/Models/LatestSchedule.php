<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatestSchedule extends Model
{
    protected $fillable = [
        'title',
        'event_date',
        'event_time',
        'description',
        'attachment_path',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}
