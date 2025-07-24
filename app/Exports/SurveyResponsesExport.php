<?php

namespace App\Exports;

use App\Models\SurveyQuestions;
use App\Models\SurveyTypes;
use App\Models\SurveyResponses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class SurveyResponsesExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    ShouldAutoSize
{
    protected string $surveyType;
    protected ?string $respondentCategory;
    protected Collection $questions;
    protected Collection $responses;
    protected array $responseStats;

    public function __construct(string $surveyType, ?string $respondentCategory = null)
    {
        $this->surveyType = $surveyType;
        $this->respondentCategory = $respondentCategory;
        $this->loadData();
        $this->calculateStats();
    }

    /**
     * Load survey data and questions
     */
    private function loadData(): void
    {
        $surveyTypeModel = SurveyTypes::where('name', strtoupper($this->surveyType))->first();

        if (!$surveyTypeModel) {
            $this->questions = collect();
            $this->responses = collect();
            return;
        }

        $this->questions = SurveyQuestions::where('survey_type_id', $surveyTypeModel->id)
            ->with('section')
            ->orderBy('survey_section_id')
            ->orderBy('sort_order')
            ->get();

        $this->responses = SurveyResponses::query()
            ->where('survey_type_id', $surveyTypeModel->id)
            ->when(
                $this->surveyType === 'vmts' && $this->respondentCategory,
                fn($q) => $q->where('respondent_category', $this->respondentCategory)
            )
            ->orderByDesc('submitted_at')
            ->get();
    }

    /**
     * Calculate response statistics for each question
     */
    private function calculateStats(): void
    {
        $this->responseStats = [];

        foreach ($this->questions as $question) {
            $stats = [
                'total_responses' => 0,
                'sangat_baik' => 0,
                'baik' => 0,
                'cukup' => 0,
                'kurang' => 0,
            ];

            foreach ($this->responses as $response) {
                if (!$response->question_answers) {
                    continue;
                }

                $answers = $response->question_answers; // Sudah array

                if (!is_array($answers)) {
                    continue;
                }

                $answer = $answers[$question->id] ?? null;

                if ($answer !== null && $answer !== '') {
                    $stats['total_responses']++;

                    $answerValue = is_array($answer) ? implode(', ', $answer) : (string)$answer;

                    $normalizedAnswer = $this->normalizeAnswer($answerValue);
                    if (array_key_exists($normalizedAnswer, $stats)) {
                        $stats[$normalizedAnswer]++;
                    }
                }
            }


            $this->responseStats[$question->id] = $stats;
        }
    }

    /**
     * Normalize answer to standard categories
     */
    private function normalizeAnswer($answer): string
    {
        if (is_array($answer)) {
            $answer = implode(', ', $answer);
        }

        $answer = strtolower(trim((string)$answer));

        $mappings = [
            'sangat baik' => 'sangat_baik',
            'baik' => 'baik',
            'cukup' => 'cukup',
            'kurang' => 'kurang',
            'sangat_baik' => 'sangat_baik',
            'sangat_kurang' => 'kurang',
            '4' => 'sangat_baik',
            '3' => 'baik',
            '2' => 'cukup',
            '1' => 'kurang',
        ];

        return $mappings[$answer] ?? 'kurang';
    }

    /**
     * Calculate percentage for a category
     */
    private function calculatePercentage(int $count, int $total): string
    {
        if ($total === 0) {
            return '0%';
        }

        return round(($count / $total) * 100) . '%';
    }

    public function collection(): Collection
    {
        $rows = collect();

        if ($this->questions->isEmpty()) {
            return $rows;
        }

        // Add section headers and questions
        $currentSection = null;
        $questionNumber = 1;
        $sectionLetter = 'A';

        foreach ($this->questions as $question) {
            $sectionTitle = $question->section ? $question->section->section_title : 'Umum';

            // Add section header if it's a new section
            if ($currentSection !== $sectionTitle) {
                $rows->push([
                    'no' => $sectionLetter,
                    'instrumen' => $sectionTitle,
                    'jumlah' => 'Jumlah Responden',
                    'sangat_baik' => 'Sangat baik',
                    'baik' => 'Baik',
                    'cukup' => 'Cukup',
                    'kurang' => 'Kurang',
                ]);
                $currentSection = $sectionTitle;
                $sectionLetter = chr(ord($sectionLetter) + 1);
            }

            $stats = $this->responseStats[$question->id] ?? [
                'total_responses' => 0,
                'sangat_baik' => 0,
                'baik' => 0,
                'cukup' => 0,
                'kurang' => 0,
            ];

            $totalResponses = $stats['total_responses'];

            $rows->push([
                'no' => $questionNumber,
                'instrumen' => $question->question_text ?? '',
                'jumlah' => $totalResponses,
                'sangat_baik' => $stats['sangat_baik'],
                'baik' => $stats['baik'],
                'cukup' => $stats['cukup'],
                'kurang' => $stats['kurang'],
            ]);

            $questionNumber++;
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'NO',
            'Instrumen Kepuasan',
            'Jumlah Responden',
            'Sangat baik',
            'Baik',
            'Cukup',
            'Kurang'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        if ($lastRow <= 1) {
            return [];
        }

        $styles = [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E6E6E6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // All cells border
            "A1:{$lastColumn}{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Center alignment for number columns
            "A1:A{$lastRow}" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Center alignment for response data
            "C1:{$lastColumn}{$lastRow}" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];

        // Add section header styling
        $sectionHeaderRows = $this->getSectionHeaderRows();
        foreach ($sectionHeaderRows as $rowNumber) {
            $styles[$rowNumber] = [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'F0F0F0'],
                ],
            ];
        }

        return $styles;
    }

    /**
     * Get section header row numbers for styling
     */
    private function getSectionHeaderRows(): array
    {
        if ($this->questions->isEmpty()) {
            return [];
        }

        $headerRows = [];
        $currentSection = null;
        $rowNumber = 2; // Start from row 2 (after headers)

        foreach ($this->questions as $question) {
            $sectionTitle = $question->section ? $question->section->section_title : 'Umum';

            if ($currentSection !== $sectionTitle) {
                $headerRows[] = $rowNumber;
                $currentSection = $sectionTitle;
                $rowNumber++; // Skip section header row
            }

            $rowNumber++; // Question row
        }

        return $headerRows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // NO
            'B' => 50,  // Instrumen Kepuasan
            'C' => 15,  // Jumlah Responden
            'D' => 12,  // Sangat baik
            'E' => 12,  // Baik
            'F' => 12,  // Cukup
            'G' => 12,  // Kurang
        ];
    }

    public function title(): string
    {
        return 'Survey Responses - ' . strtoupper($this->surveyType);
    }
}
