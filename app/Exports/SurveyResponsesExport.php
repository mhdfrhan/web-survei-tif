<?php

namespace App\Exports;

use App\Models\SurveyQuestions;
use App\Models\SurveyTypes;
use App\Models\SurveyResponses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SurveyResponsesExport implements FromCollection, WithHeadings
{
    protected $surveyType;
    protected $respondentCategory;

    public function __construct($surveyType, $respondentCategory = null)
    {
        $this->surveyType = $surveyType;
        $this->respondentCategory = $respondentCategory;
    }

    public function collection()
    {
        $surveyTypeModel = SurveyTypes::where('name', strtoupper($this->surveyType))->first();

        $questions = [];
        if ($surveyTypeModel) {
            $questions = SurveyQuestions::where('survey_type_id', $surveyTypeModel->id)
                ->pluck('question_text', 'id')
                ->toArray();
        }

        $query = SurveyResponses::query()
            ->when($surveyTypeModel, fn($q) => $q->where('survey_type_id', $surveyTypeModel->id))
            ->when(
                $this->surveyType === 'vmts' && $this->respondentCategory,
                fn($q) => $q->where('respondent_category', $this->respondentCategory)
            )
            ->orderByDesc('submitted_at');

        $data = $query->get();

        // Ambil semua key form_data unik
        $formDataKeys = [];
        foreach ($data as $item) {
            $formData = json_decode($item->form_data, true) ?? [];
            foreach (array_keys($formData) as $key) {
                $formDataKeys[$key] = true;
            }
        }
        $formDataKeys = array_keys($formDataKeys);

        $rows = $data->map(function ($item) use ($questions, $formDataKeys) {
            $formData = json_decode($item->form_data, true) ?? [];
            $answers = json_decode($item->question_answers, true) ?? [];

            // Ganti key ID dengan teks pertanyaan
            $answersWithText = [];
            foreach ($questions as $qid => $label) {
                $ans = $answers[$qid] ?? '';
                $answersWithText[$label] = is_array($ans) ? json_encode($ans) : $ans;
            }

            // Susun formData sesuai urutan key
            $orderedFormData = [];
            foreach ($formDataKeys as $key) {
                $orderedFormData[$key] = $formData[$key] ?? '';
            }

            return array_merge([
                'ID' => $item->id,
                'Kategori' => $item->respondent_category,
                'Tanggal' => $item->submitted_at,
                'IP' => $item->ip_address,
            ], $orderedFormData, $answersWithText);
        });

        // Tambahkan baris total di akhir
        $totalRow = array_merge([
            'ID' => '',
            'Kategori' => '',
            'Tanggal' => '',
            'IP' => '',
        ], array_fill(0, count($formDataKeys), ''), array_fill(0, count($questions), ''));

        $totalRow['ID'] = 'TOTAL';
        $totalRow['Kategori'] = '';
        $totalRow['Tanggal'] = '';
        $totalRow['IP'] = '';
        // Kolom pertama diisi "TOTAL", kolom kedua diisi jumlah data
        $firstKey = array_key_first($totalRow);
        $totalRow[$firstKey] = 'TOTAL';
        $secondKey = array_keys($totalRow)[1];
        $totalRow[$secondKey] = $data->count();

        $rows->push($totalRow);

        return $rows;
    }

    public function headings(): array
    {
        $surveyTypeModel = SurveyTypes::where('name', strtoupper($this->surveyType))->first();
        $questions = [];
        if ($surveyTypeModel) {
            $questions = SurveyQuestions::where('survey_type_id', $surveyTypeModel->id)
                ->pluck('question_text', 'id')
                ->toArray();
        }

        // Ambil semua data untuk dapatkan key form_data unik
        $query = SurveyResponses::query()
            ->when($surveyTypeModel, fn($q) => $q->where('survey_type_id', $surveyTypeModel->id))
            ->when(
                $this->surveyType === 'vmts' && $this->respondentCategory,
                fn($q) => $q->where('respondent_category', $this->respondentCategory)
            );
        $data = $query->get();

        $formDataKeys = [];
        foreach ($data as $item) {
            $formData = json_decode($item->form_data, true) ?? [];
            foreach (array_keys($formData) as $key) {
                $formDataKeys[$key] = true;
            }
        }
        $formDataKeys = array_keys($formDataKeys);

        return array_merge(
            ['ID', 'Kategori', 'Tanggal', 'IP'],
            $formDataKeys,
            array_values($questions)
        );
    }
}
