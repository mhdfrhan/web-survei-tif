<?php

namespace App\Livewire\Dashboard\Survey;

use Livewire\Component;
use App\Models\SurveyFormFields;
use App\Models\SurveyQuestions;
use App\Models\SurveySections;
use App\Models\SurveyTypes;
use App\Services\SurveyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TendikForm extends Component
{
    // Survey Type Properties
    public $surveyType;
    public $title = 'Survei Pengukuran Pemahaman Tendik Fakultas Ilmu Komputer';
    public $description = '';
    public $isActive = true;
    public $sections = [];
    public $showSectionModal = false;


    // Form Fields Properties
    public $formFields = [];
    public $newFormField = [
        'field_name' => '',
        'field_label' => '',
        'field_type' => 'text',
        'field_options' => [],
        'is_required' => false,
        'sort_order' => 0
    ];

    // Survey Questions Properties
    public $questions = [];
    public $newQuestion = [
        'question_text' => '',
        'question_type' => 'rating',
        'options' => [],
        'min_value' => 1,
        'max_value' => 4,
        'rating_labels' => [
            '1' => 'Kurang',
            '2' => 'Cukup',
            '3' => 'Baik',
            '4' => 'Sangat Baik'
        ],
        'is_required' => true,
        'sort_order' => 0
    ];

    public $newSection = [
        'section_title' => '',
        'section_description' => '',
        'slug' => '',
    ];

    // UI State Properties
    public $showFormFieldModal = false;
    public $showQuestionModal = false;
    public $editingFormFieldIndex = null;
    public $editingQuestionIndex = null;
    public $activeTab = 'info';

    // Field Type Options
    public $fieldTypes = [
        'text' => 'Text',
        'number' => 'Number',
        'email' => 'Email',
        'date' => 'Date',
        'select' => 'Select',
        'textarea' => 'Textarea'
    ];

    // Question Type Options
    public $questionTypes = [
        'rating' => 'Rating Scale',
        'text' => 'Text Input',
    ];

    protected $surveyService;

    // public function boot(SurveyService $surveyService)
    // {
    //     $this->surveyService = $surveyService;
    // }

    public function mount()
    {
        $this->loadSections();
        $this->loadTendikSurvey();
    }

    public function loadSections()
    {
        $surveyType = SurveyTypes::where('name', 'TENDIK')->first();
        if ($surveyType) {
            $this->sections = SurveySections::where('survey_type_id', $surveyType->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->toArray();
        }
    }

    public function loadTendikSurvey()
    {
        $this->surveyType = SurveyTypes::where('name', 'TENDIK')
            ->with(['formFields' => function ($query) {
                $query->orderBy('sort_order');
            }, 'questions' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->first();

        if ($this->surveyType) {
            $this->title = $this->surveyType->title;
            $this->description = $this->surveyType->description ?? '';
            $this->isActive = $this->surveyType->is_active;
            $this->formFields = $this->surveyType->formFields->toArray();
            $this->questions = $this->surveyType->questions->toArray();
        }
    }

    public function saveSurveyInfo()
    {
        $this->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'isActive' => 'boolean'
        ]);

        try {
            if ($this->surveyType) {
                $this->surveyType->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'is_active' => $this->isActive
                ]);
            } else {
                $this->surveyType = SurveyTypes::create([
                    'name' => 'TENDIK',
                    'title' => $this->title,
                    'description' => $this->description,
                    'is_active' => $this->isActive
                ]);
            }

            $this->dispatch('notify', type: 'success', message: 'Survey info berhasil disimpan!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());
        }
    }

    public function editFormField($index)
    {
        $this->editingFormFieldIndex = $index;
        $this->newFormField = $this->formFields[$index];
        $this->dispatch('open-modal', 'add-form-field-modal');
    }

    public function saveFormField()
    {
        $this->validate([
            'newFormField.field_name' => 'required|string|max:255',
            'newFormField.field_label' => 'required|string|max:255',
            'newFormField.field_type' => 'required|string|in:text,number,email,date,select,textarea'
        ]);

        if ($this->editingFormFieldIndex !== null) {
            $this->formFields[$this->editingFormFieldIndex] = $this->newFormField;
        } else {
            $this->newFormField['sort_order'] = count($this->formFields) + 1;
            $this->formFields[] = $this->newFormField;
        }

        $this->dispatch('close-modal', 'add-form-field-modal');
        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Form field berhasil disimpan!'
        );
        $this->resetFormFieldData();
    }

    public function deleteFormField($index)
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Form field berhasil dihapus!'
        );
    }

    public function resetFormFieldData()
    {
        $this->newFormField = [
            'field_name' => '',
            'field_label' => '',
            'field_type' => 'text',
            'field_options' => [],
            'is_required' => false,
            'sort_order' => 0
        ];
    }

    public function editQuestion($index)
    {
        $this->editingQuestionIndex = $index;
        $this->newQuestion = $this->questions[$index];
        $this->dispatch('open-modal', 'add-question-modal');
    }

    public function saveQuestion()
    {
        $this->validate([
            'newQuestion.survey_section_id' => 'required|exists:survey_sections,id',
            'newQuestion.question_text' => 'required|string',
            'newQuestion.question_type' => 'required|string|in:rating,text,multiple_choice,checkbox',
            'newQuestion.question_text' => 'required|string',
            'newQuestion.question_type' => 'required|string|in:rating,text,multiple_choice,checkbox'
        ]);

        if ($this->editingQuestionIndex !== null) {
            $this->questions[$this->editingQuestionIndex] = $this->newQuestion;
        } else {
            $this->newQuestion['sort_order'] = count($this->questions) + 1;
            $this->questions[] = $this->newQuestion;
        }

        $this->dispatch('close-modal', 'add-question-modal');
        $this->resetQuestionData();
        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Question berhasil disimpan!'
        );
    }

    public function deleteQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Question berhasil dihapus!'
        );
    }


    public function resetQuestionData()
    {
        $this->newQuestion = [
            'question_text' => '',
            'question_type' => 'rating',
            'options' => [],
            'min_value' => 1,
            'max_value' => 4,
            'rating_labels' => [
                '1' => 'Kurang',
                '2' => 'Cukup',
                '3' => 'Baik',
                '4' => 'Sangat Baik'
            ],
            'is_required' => true,
            'sort_order' => 0
        ];
    }

    // Save All Data
    public function saveAllData()
    {
        if (!$this->surveyType) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Simpan informasi survey terlebih dahulu!'
            ]);
            return;
        }

        try {
            DB::transaction(function () {
                // Save form fields
                $this->surveyType->formFields()->delete();
                foreach ($this->formFields as $fieldData) {
                    SurveyFormFields::create(array_merge($fieldData, [
                        'survey_type_id' => $this->surveyType->id,
                        'is_active' => true
                    ]));
                }

                // Save questions
                $this->surveyType->questions()->delete();
                foreach ($this->questions as $questionData) {
                    SurveyQuestions::create(array_merge($questionData, [
                        'survey_type_id' => $this->surveyType->id,
                        'is_active' => true
                    ]));
                }
            });

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Survey Tendik berhasil disimpan!'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: $e->getMessage()
            );
        }
    }

    public function addSelectOption($field = 'newFormField')
    {
        $this->{$field}['field_options'][] = '';
    }

    public function removeSelectOption($field, $index)
    {
        unset($this->{$field}['field_options'][$index]);
        $this->{$field}['field_options'] = array_values($this->{$field}['field_options']);
    }

    public function addMultipleChoiceOption()
    {
        $this->newQuestion['options'][] = '';
    }

    public function removeMultipleChoiceOption($index)
    {
        unset($this->newQuestion['options'][$index]);
        $this->newQuestion['options'] = array_values($this->newQuestion['options']);
    }

    public function openQuestionModal()
    {
        $this->resetQuestionData();
        $this->editingQuestionIndex = null;
    }

    public function openFormFieldModal()
    {
        $this->resetFormFieldData();
        $this->editingFormFieldIndex = null;
    }

    public function openSectionModal()
    {
        $this->resetSectionData();
    }


    public function saveSection()
    {
        if (!$this->surveyType) {
            $this->dispatch('notify', type: 'error', message: 'Simpan informasi survey terlebih dahulu!');
            return;
        }

        try {
            $this->validate([
                'newSection.section_title' => 'required|string|max:255',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'Validation error: ' . $th->getMessage());
            return;
        }

        try {
            $section = SurveySections::create([
                'survey_type_id' => $this->surveyType->id,
                'section_title' => $this->newSection['section_title'],
                'section_description' => $this->newSection['section_description'],
                'slug' => Str::slug($this->newSection['section_title']),
                'sort_order' => count($this->sections) + 1,
                'is_active' => true,
            ]);

            $this->sections[] = $section->toArray();
            $this->dispatch('close-modal', 'add-section-modal');
            $this->resetSectionData();
            $this->dispatch('notify', type: 'success', message: 'Section berhasil ditambahkan!');
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: 'Terjadi kesalahan saat menambahkan section: ' . $th->getMessage());
        }
    }

    public function resetSectionData()
    {
        $this->newSection = [
            'section_title' => '',
            'section_description' => '',
            'slug' => '',
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.survey.tendik-form');
    }
}
