<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireSuggestion extends Model
{
    protected $table = 'suggestions';

    protected $fillable = [
        'guest_name',
        'table_number',
        'suggestion',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'suggestion_id');
    }
}
