<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralLink extends Model
{
    protected $fillable = [
        'name',
        'type',
        'link',
        'description',
        'logo_path',
        'status',
    ];
}
