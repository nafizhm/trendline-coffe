<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireAnswer extends Model
{
    protected $table = 'question_answers';

    protected $fillable = [
        'suggestion_id',
        'question_id',
        'answer_text',
    ];

    public function suggestion(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSuggestion::class, 'suggestion_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireQuestion::class, 'question_id');
    }
}
