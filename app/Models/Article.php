<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'published_at',
    'title',
    'category_id',
    'content',
    'attachment_path',
    'status',
    'admin_name',
])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
