<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireQuestion extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'question_text',
        'placeholder',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'question_id');
    }
}
