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
        'entry_value',
        'target_value',
        'stop_value',
        'description',
        'sort_order',
    ];
}
