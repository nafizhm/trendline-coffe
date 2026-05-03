<?php

namespace App\Support;

use App\Models\QuestionnaireSuggestion;
use FPDF;

class QuestionnaireReportPdf extends FPDF
{
    public function __construct(private readonly string $periodLabel)
    {
        parent::__construct('P', 'mm', 'A4');

        $this->SetMargins(12, 12, 12);
        $this->SetAutoPageBreak(true, 12);
    }

    public function Header(): void
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 8, $this->encode('Laporan Kuesioner'), 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, $this->encode('Periode: '.$this->periodLabel), 0, 1, 'C');

        $this->Ln(2);
        $this->SetDrawColor(203, 213, 225);
        $this->Line(12, $this->GetY(), 198, $this->GetY());
        $this->Ln(5);
    }

    public function Footer(): void
    {
        $this->SetY(-10);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(0, 5, $this->encode('Halaman '.$this->PageNo()), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    public function renderSummary(int $total): void
    {
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 7, $this->encode('Ringkasan Laporan'), 0, 1);

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(226, 232, 240);
        $this->Cell(0, 10, $this->encode('Total respons pada periode ini: '.$total), 1, 1, 'L', true);
        $this->Ln(5);
    }

    public function renderEmptyState(): void
    {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(226, 232, 240);
        $this->MultiCell(0, 8, $this->encode('Tidak ada data kuesioner pada periode yang dipilih.'), 1, 'L', true);
    }

    public function renderSuggestion(int $number, QuestionnaireSuggestion $suggestion): void
    {
        $answers = $suggestion->answers
            ->map(fn ($answer) => [
                'question' => $answer->question?->question_text ?: 'Pertanyaan',
                'answer' => $answer->answer_text ?: '-',
            ])
            ->values();

        $estimatedHeight = 28
            + $this->estimateTextHeight('Saran: '.($suggestion->suggestion ?: '-'))
            + $answers->sum(function (array $answer): float {
                return $this->estimateTextHeight($answer['question'] ?: 'Pertanyaan')
                    + $this->estimateTextHeight($answer['answer'] ?: '-')
                    + 8;
            });

        $this->ensureSpace($estimatedHeight);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 7, $this->encode('Respon #'.$number), 0, 1);

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, $this->encode('Tanggal Kirim: '.($suggestion->created_at?->format('d M Y H:i') ?: '-')), 0, 1);
        $this->Ln(1);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, $this->encode('Saran'), 0, 1);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 6, $this->encode($suggestion->suggestion ?: '-'), 1, 'L');
        $this->Ln(2);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, $this->encode('Jawaban Pertanyaan'), 0, 1);
        $this->SetFont('Arial', '', 10);

        if ($answers->isEmpty()) {
            $this->MultiCell(0, 6, $this->encode('Belum ada jawaban pertanyaan.'), 1, 'L');
        } else {
            foreach ($answers as $answer) {
                $this->SetFillColor(248, 250, 252);
                $this->SetDrawColor(226, 232, 240);

                $this->SetFont('Arial', 'B', 9);
                $this->MultiCell(0, 6, $this->encode($answer['question'] ?: 'Pertanyaan'), 1, 'L', true);

                $this->SetFont('Arial', 'I', 9);
                $this->MultiCell(0, 6, $this->encode($answer['answer'] ?: '-'), 1, 'L');
                $this->Ln(1.5);
            }
        }

        $this->Ln(5);
    }

    public function encode(?string $text): string
    {
        $value = trim((string) $text);

        if ($value === '') {
            return '-';
        }

        $encoded = iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $value);

        return $encoded !== false ? $encoded : preg_replace('/[^\x20-\x7E]/', '', $value);
    }

    private function ensureSpace(float $height): void
    {
        if ($this->GetY() + $height > $this->GetPageHeight() - 12) {
            $this->AddPage();
        }
    }

    private function estimateTextHeight(string $text, float $lineHeight = 6): float
    {
        $charactersPerLine = 95;
        $lines = max(1, (int) ceil(mb_strlen($text) / $charactersPerLine));

        return $lines * $lineHeight;
    }
}
