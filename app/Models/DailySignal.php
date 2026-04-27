<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySignal extends Model
{
    protected $fillable = [
        'type',
        'symbol',
        'pair_name',
        'position',
        'signal_date',
        'signal_time',
        'entry_value',
        'target_value',
        'stop_value',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'signal_date' => 'date',
    ];
}
