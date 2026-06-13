<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'published_at',
    'title',
    'category_id',
    'youtube_code',
    'status',
    'admin_name',
    'sort_order',
])]
class Video extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
