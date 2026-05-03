<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $externalConnection = config('database.connections.questionnaire.database')
            ? 'questionnaire'
            : null;

        if (! $externalConnection) {
            return;
        }

        $defaultDatabase = config('database.connections.mysql.database');
        $externalDatabase = config('database.connections.questionnaire.database');

        if ($defaultDatabase === $externalDatabase) {
            return;
        }

        if (! Schema::connection($externalConnection)->hasTable('questions')
            || ! Schema::connection($externalConnection)->hasTable('suggestions')
            || ! Schema::connection($externalConnection)->hasTable('question_answers')) {
            return;
        }

        $questions = DB::connection($externalConnection)->table('questions')->get();
        if ($questions->isNotEmpty()) {
            DB::table('questions')->upsert(
                $questions->map(fn ($question) => (array) $question)->all(),
                ['id'],
                ['question_text', 'placeholder', 'sort_order', 'is_active', 'created_at', 'updated_at']
            );
        }

        $suggestions = DB::connection($externalConnection)->table('suggestions')->get();
        if ($suggestions->isNotEmpty()) {
            DB::table('suggestions')->upsert(
                $suggestions->map(fn ($suggestion) => (array) $suggestion)->all(),
                ['id'],
                ['guest_name', 'table_number', 'suggestion', 'created_at', 'updated_at']
            );
        }

        $answers = DB::connection($externalConnection)->table('question_answers')->get();
        if ($answers->isNotEmpty()) {
            DB::table('question_answers')->upsert(
                $answers->map(fn ($answer) => (array) $answer)->all(),
                ['id'],
                ['suggestion_id', 'question_id', 'answer_text', 'created_at', 'updated_at']
            );
        }
    }

    public function down(): void
    {
        //
    }
};
