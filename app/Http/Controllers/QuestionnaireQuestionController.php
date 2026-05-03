<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionnaireQuestionController extends Controller
{
    public function index(): View
    {
        return view('questionnaire-questions.index', [
            'questions' => QuestionnaireQuestion::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:255'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'sort_order.required' => 'Urutan tampil wajib diisi.',
        ]);

        QuestionnaireQuestion::create([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('questionnaire-questions.index')
            ->with('status', 'Pertanyaan kuesioner berhasil ditambahkan.');
    }

    public function update(Request $request, QuestionnaireQuestion $questionnaireQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:255'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'sort_order.required' => 'Urutan tampil wajib diisi.',
        ]);

        $questionnaireQuestion->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('questionnaire-questions.index')
            ->with('status', 'Pertanyaan kuesioner berhasil diperbarui.');
    }

    public function destroy(QuestionnaireQuestion $questionnaireQuestion): RedirectResponse
    {
        $questionnaireQuestion->delete();

        return redirect()
            ->route('questionnaire-questions.index')
            ->with('status', 'Pertanyaan kuesioner berhasil dihapus.');
    }
}
