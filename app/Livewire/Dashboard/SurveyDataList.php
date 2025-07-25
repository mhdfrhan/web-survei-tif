<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SurveyTypes;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyResponsesExport;
use App\Exports\SurveyResponsesVMTSExport;
use App\Models\SurveyFormFields;
use App\Models\SurveyQuestions;
use App\Models\SurveyResponses;
use App\Models\SurveySections;

class SurveyDataList extends Component
{
    use WithPagination;

    public $surveyType = 'vmts';
    public $respondentCategory = '';
    public $perPage = 30;
    public $hasMorePages = true;
    public $showViewResponseModal = false;
    public $selectedResponse;
    public $formFields = [];
    public $questions = [];

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'surveyType' => ['except' => 'vmts'],
        'respondentCategory' => ['except' => ''],
    ];

    public function updatingSurveyType()
    {
        $this->resetPage();
        $this->respondentCategory = '';
    }

    public function updatingRespondentCategory()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 30;
    }

    public function exportExcel()
    {
        if ($this->surveyType === 'vmts') {
            $filename = 'survey_vmts_fakultas_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new SurveyResponsesVMTSExport($this->surveyType, $this->respondentCategory), $filename);
        } elseif ($this->surveyType === 'vmtstif'){
            $filename = 'survey_vmts_prodi_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new SurveyResponsesVMTSExport($this->surveyType, $this->respondentCategory), $filename);
        } else {
            $filename = 'survey_' . $this->surveyType . '_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new SurveyResponsesExport($this->surveyType, $this->respondentCategory), $filename);
        }
    }

    public function getSurveyTypesProperty()
    {
        return [
            'vmts' => 'VMTS Fakultas',
            'vmtstif' => 'VMTS Prodi',
            'dosen' => 'Dosen',
            'tendik' => 'Tendik',
            'mahasiswa' => 'Mahasiswa',
        ];
    }

    public function getRespondentCategoriesProperty()
    {
        return [
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'tendik' => 'Tendik',
        ];
    }

    public function viewResponse($id)
    {
        $response = SurveyResponses::find($id);
        $this->selectedResponse = $response;
        $surveyType = $response->survey_type_id;
        $this->formFields = SurveyFormFields::where('survey_type_id', $surveyType)->get()->toArray();
        $this->questions = SurveyQuestions::where('survey_type_id', $surveyType)->get()->toArray();
    }

    public function render()
    {
        $surveyTypeModel = SurveyTypes::where('name', strtoupper($this->surveyType))->first();
        $query = SurveyResponses::query()
            ->when($surveyTypeModel, fn($q) => $q->where('survey_type_id', $surveyTypeModel->id))
            ->when(
                in_array($this->surveyType, ['vmts', 'vmtstif']) && $this->respondentCategory,
                fn($q) => $q->where('respondent_category', $this->respondentCategory)
            )
            ->orderByDesc('submitted_at');

        $responses = $query->paginate($this->perPage);

        $this->hasMorePages = $responses->hasMorePages();

        // Ambil sections beserta pertanyaannya
        $sections = [];
        if ($surveyTypeModel) {
            $sections = SurveySections::with(['questions' => function ($q) {
                $q->orderBy('sort_order');
            }])
                ->where('survey_type_id', $surveyTypeModel->id)
                ->orderBy('sort_order')
                ->get();
        }

        return view('livewire.dashboard.survey-data-list', [
            'responses' => $responses,
            'surveyTypes' => $this->surveyTypes,
            'respondentCategories' => $this->respondentCategories,
            'sections' => $sections, // <-- tambahkan ini
        ]);
    }
}
