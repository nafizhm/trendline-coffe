<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSuggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionnaireFrontendController extends Controller
{
    public function index(): View
    {
        return view('kuesioner.index', [
            'questions' => QuestionnaireQuestion::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $questions = QuestionnaireQuestion::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $rules = [
        ];

        foreach ($questions as $question) {
            $rules['answers.'.$question->id] = ['required', 'string', 'max:1000'];
            $messages['answers.'.$question->id.'.required'] = 'Jawaban pertanyaan ini wajib diisi.';
        }

        $validated = $request->validate($rules, $messages ?? []);

        $suggestion = QuestionnaireSuggestion::create([
            'suggestion' => 'Tanpa saran tambahan.',
        ]);

        foreach ($questions as $question) {
            $answer = trim((string) data_get($validated, 'answers.'.$question->id, ''));

            if ($answer === '') {
                continue;
            }

            $suggestion->answers()->create([
                'question_id' => $question->id,
                'answer_text' => $answer,
            ]);
        }

        return redirect()->route('kuesioner.thank-you');
    }

    public function thankYou(): View
    {
        return view('kuesioner.thanks');
    }
}
