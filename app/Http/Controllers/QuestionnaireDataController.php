<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireSuggestion;
use App\Support\QuestionnaireReportPdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class QuestionnaireDataController extends Controller
{
    public function index(): View
    {
        return view('questionnaires.index', [
            'suggestions' => QuestionnaireSuggestion::query()
                ->with(['answers.question'])
                ->latest()
                ->get(),
        ]);
    }

    public function printReport(Request $request): Response
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'start_date.required' => 'Tanggal periode awal wajib diisi.',
            'end_date.required' => 'Tanggal periode akhir wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal periode akhir harus sama atau setelah tanggal awal.',
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $suggestions = QuestionnaireSuggestion::query()
            ->with(['answers.question'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        $pdf = new QuestionnaireReportPdf(
            periodLabel: sprintf(
                '%s - %s',
                $startDate->translatedFormat('d M Y'),
                $endDate->translatedFormat('d M Y')
            )
        );

        $pdf->SetTitle($pdf->encode('Laporan Kuesioner'));
        $pdf->SetAuthor($pdf->encode(config('app.name', 'Trendline Coffee')));
        $pdf->AddPage();
        $pdf->renderSummary($suggestions->count());

        foreach ($suggestions as $index => $suggestion) {
            $pdf->renderSuggestion($index + 1, $suggestion);
        }

        if ($suggestions->isEmpty()) {
            $pdf->renderEmptyState();
        }

        $fileName = sprintf(
            'laporan-kuesioner-%s-sd-%s.pdf',
            $startDate->format('Ymd'),
            $endDate->format('Ymd')
        );

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    public function destroy(QuestionnaireSuggestion $questionnaire): RedirectResponse
    {
        $questionnaire->delete();

        return redirect()
            ->route('questionnaires.index')
            ->with('status', 'Data kuesioner berhasil dihapus.');
    }
}
