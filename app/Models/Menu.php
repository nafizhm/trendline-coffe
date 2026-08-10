<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'menu_category_id',
    'name',
    'short_description',
    'long_description',
    'price',
    'hero',
    'photo_path',
    'tag',
    'is_recommended',
    'sort_order',
    'status',
])]
class Menu extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_recommended' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
