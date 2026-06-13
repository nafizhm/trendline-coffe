<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'type',
    'description',
])]
class Category extends Model
{
    public const TYPE_ARTICLE = 'artikel';
    public const TYPE_VIDEO = 'video';
}
