<?php

namespace App\Exports;

use App\Models\SurveyQuestions;
use App\Models\SurveyTypes;
use App\Models\SurveyResponses;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Log;

class SurveyResponsesVMTSExport implements FromCollection, WithHeadings, WithStyles
{
    protected string $surveyType;
    protected ?string $respondentCategory;

    protected $years = [2021, 2022, 2023];
    protected $categories = ['dosen' => 'Dosen', 'tendik' => 'Tendik', 'mahasiswa' => 'Mahasiswa'];
    protected $scores = [
        'sangat_baik' => 'Sangat Baik',
        'baik' => 'Baik',
        'cukup' => 'Cukup',
        'kurang' => 'Kurang',
    ];

    public function __construct(string $surveyType, ?string $respondentCategory = null)
    {
        $this->surveyType = $surveyType;
        $this->respondentCategory = $respondentCategory;
    }

    public function collection()
    {
        // Debug: Cari survey type dengan berbagai variasi
        $surveyTypeVariations = [
            strtoupper($this->surveyType), // VMTS
            strtolower($this->surveyType), // vmts
            ucfirst(strtolower($this->surveyType)), // Vmts
            $this->surveyType, // sesuai input
        ];

        $surveyTypeModel = null;
        foreach ($surveyTypeVariations as $variation) {
            $surveyTypeModel = SurveyTypes::where('name', $variation)->first();
            if ($surveyTypeModel) {
                Log::info("Survey Type ditemukan: {$variation}", ['id' => $surveyTypeModel->id]);
                break;
            }
        }

        // Jika tidak ditemukan, log semua survey types
        if (!$surveyTypeModel) {
            $allTypes = SurveyTypes::pluck('name', 'id')->toArray();
            Log::error("Survey Type '{$this->surveyType}' tidak ditemukan. Available types:", $allTypes);
            return collect([]);
        }

        // Ambil questions
        $questions = SurveyQuestions::where('survey_type_id', $surveyTypeModel->id)
            ->orderBy('survey_section_id')
            ->orderBy('sort_order')
            ->get();

        Log::info("Questions ditemukan: {$questions->count()}");

        if ($questions->isEmpty()) {
            Log::warning("Tidak ada questions untuk survey_type_id: {$surveyTypeModel->id}");
            return collect([]);
        }

        // Debug: Cek kategori responden yang tersedia
        $availableCategories = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)
            ->whereNotNull('respondent_category')
            ->distinct()
            ->pluck('respondent_category')
            ->toArray();
        Log::info("Kategori responden tersedia:", $availableCategories);

        // Debug: Cek tahun yang tersedia
        $availableYears = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)
            ->selectRaw('YEAR(submitted_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
        Log::info("Tahun data tersedia:", $availableYears);

        $rows = [];
        $no = 1;

        foreach ($questions as $q) {
            $row = [
                'no' => $no,
                'question' => $q->question_text,
            ];

            foreach ($this->years as $year) {
                foreach ($this->categories as $catKey => $catLabel) {
                    // Ambil semua response untuk tahun, kategori, dan pertanyaan ini
                    $responses = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)
                        ->where('respondent_category', $catKey)
                        // ->whereYear('submitted_at', $year)
                        ->get();

                    Log::info("Responses untuk question {$q->id}, year {$year}, category {$catKey}: {$responses->count()}");

                    $counts = [
                        'sangat_baik' => 0,
                        'baik' => 0,
                        'cukup' => 0,
                        'kurang' => 0,
                    ];

                    foreach ($responses as $resp) {
                        $answers = $resp->question_answers;

                        // Handle jika question_answers masih string JSON
                        if (is_string($answers)) {
                            $answers = json_decode($answers, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                Log::warning("JSON decode error untuk response {$resp->id}: " . json_last_error_msg());
                                continue;
                            }
                        }

                        if (!is_array($answers)) {
                            Log::warning("question_answers bukan array untuk response {$resp->id}, type: " . gettype($answers));
                            continue;
                        }

                        // Coba berbagai format key untuk question
                        $possibleKeys = [
                            $q->id,
                            (string)$q->id,
                            "question_{$q->id}",
                        ];

                        $answer = null;
                        foreach ($possibleKeys as $key) {
                            if (array_key_exists($key, $answers)) {
                                $answer = $answers[$key];
                                break;
                            }
                        }

                        if ($answer === null || $answer === '') {
                            continue;
                        }

                        $normalized = $this->normalizeAnswer($answer);
                        if (isset($counts[$normalized])) {
                            $counts[$normalized]++;
                        } else {
                            Log::warning("Answer tidak bisa dinormalisasi: '{$answer}' untuk question {$q->id}");
                        }
                    }

                    // Log counts untuk debugging
                    $totalCount = array_sum($counts);
                    if ($totalCount > 0) {
                        Log::info("Counts untuk Q{$q->id}-{$year}-{$catKey}:", $counts);
                    }

                    // Tambahkan ke row
                    foreach ($this->scores as $scoreKey => $scoreLabel) {
                        $row[$year . '_' . $catKey . '_' . $scoreKey] = $counts[$scoreKey] ?? 0;
                    }
                }
            }
            $rows[] = $row;
            $no++;
        }

        Log::info("Total rows yang akan diexport: " . count($rows));

        return collect($rows);
    }

    public function headings(): array
    {
        // Baris 1: Tahun (merge 12 kolom per tahun)
        $row1 = ['No', 'Kuesioner'];
        foreach ($this->years as $year) {
            $row1 = array_merge($row1, array_fill(0, 12, $year));
        }

        // Baris 2: Kategori (4 kolom per kategori)
        $row2 = ['', ''];
        foreach ($this->years as $year) {
            foreach ($this->categories as $cat) {
                $row2 = array_merge($row2, array_fill(0, 4, $cat));
            }
        }

        // Baris 3: Skor
        $row3 = ['', ''];
        foreach ($this->years as $year) {
            foreach ($this->categories as $cat) {
                foreach ($this->scores as $score) {
                    $row3[] = $score;
                }
            }
        }

        return [$row1, $row2, $row3];
    }

    public function styles(Worksheet $sheet)
    {
        // Header bold, center, fill
        $sheet->getStyle('A1:ZZ3')->getFont()->setBold(true);
        $sheet->getStyle('A1:ZZ3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:ZZ3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:ZZ3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6E6');

        // Border seluruh tabel
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestCol . $highestRow)
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Wrap text di header
        $sheet->getStyle('A1:ZZ3')->getAlignment()->setWrapText(true);

        // Tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(24);
        $sheet->getRowDimension(3)->setRowHeight(24);

        // Merge cell header
        $col = 3;
        foreach ($this->years as $year) {
            $start = $col;
            $end = $col + 12 - 1;
            $sheet->mergeCellsByColumnAndRow($start, 1, $end, 1);

            foreach (range(0, 2) as $i) {
                $catStart = $col + $i * 4;
                $catEnd = $catStart + 3;
                $sheet->mergeCellsByColumnAndRow($catStart, 2, $catEnd, 2);
            }
            $col += 12;
        }

        // Merge No dan Kuesioner
        $sheet->mergeCells('A1:A3');
        $sheet->mergeCells('B1:B3');

        return [];
    }

    private function normalizeAnswer($answer)
    {
        if (is_array($answer)) {
            $answer = implode(', ', $answer);
        }

        $answer = strtolower(trim((string)$answer));

        $mappings = [
            // Bahasa Indonesia
            'sangat baik' => 'sangat_baik',
            'baik' => 'baik',
            'cukup' => 'cukup',
            'kurang' => 'kurang',
            'sangat_baik' => 'sangat_baik',
            'sangat_kurang' => 'kurang',

            // Numeric
            '4' => 'sangat_baik',
            '3' => 'baik',
            '2' => 'cukup',
            '1' => 'kurang',

            // Alternatives
            'sb' => 'sangat_baik',
            'b' => 'baik',
            'c' => 'cukup',
            'k' => 'kurang',

            // English
            'excellent' => 'sangat_baik',
            'very good' => 'sangat_baik',
            'good' => 'baik',
            'fair' => 'cukup',
            'poor' => 'kurang',
        ];

        $result = $mappings[$answer] ?? null;

        // Partial matching jika tidak ditemukan exact match
        if (!$result) {
            foreach ($mappings as $key => $value) {
                if (str_contains($answer, $key) || str_contains($key, $answer)) {
                    $result = $value;
                    break;
                }
            }
        }

        return $result ?? 'kurang';
    }

    // Method untuk debugging - panggil ini di controller
    public function debugInfo(): array
    {
        $surveyTypeModel = SurveyTypes::where('name', 'LIKE', "%{$this->surveyType}%")->first();

        $info = [
            'survey_type_input' => $this->surveyType,
            'survey_type_found' => $surveyTypeModel ? $surveyTypeModel->name : 'NOT FOUND',
            'survey_type_id' => $surveyTypeModel ? $surveyTypeModel->id : null,
        ];

        if ($surveyTypeModel) {
            $info['questions_count'] = SurveyQuestions::where('survey_type_id', $surveyTypeModel->id)->count();
            $info['responses_count'] = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)->count();
            $info['available_categories'] = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)
                ->distinct()
                ->pluck('respondent_category')
                ->toArray();
            $info['available_years'] = SurveyResponses::where('survey_type_id', $surveyTypeModel->id)
                ->selectRaw('YEAR(submitted_at) as year')
                ->distinct()
                ->pluck('year')
                ->toArray();
        }

        return $info;
    }
}
