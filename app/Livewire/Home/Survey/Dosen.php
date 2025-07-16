<?php

namespace App\Livewire\Home\Survey;

use App\Models\SurveyResponses;
use App\Models\SurveyTypes;
use Livewire\Component;

class Dosen extends Component
{
    public $surveyTitle;
    public $surveyDescription;
    public $formFields = [];
    public $questions = [];
    public $formData = [];
    public $answers = [];

    public function mount()
    {
        $survey = SurveyTypes::where('name', 'DOSEN')
            ->with([
                'formFields' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                },
                'questions' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                }
            ])->first();

        if ($survey) {
            $this->surveyTitle = $survey->title;
            $this->surveyDescription = $survey->description;
            $this->formFields = $survey->formFields;
            $this->questions = $survey->questions;
        }
    }

    public function submit()
    {
        // Validasi dinamis
        $rules = [];
        foreach ($this->formFields as $f) {
            if ($f['is_required']) {
                $rules['formData.' . $f['field_name']] = 'required';
            }
        }
        foreach ($this->questions as $q) {
            if ($q['is_required']) {
                $rules['answers.' . $q['id']] = 'required';
            }
        }
        $messages = [
            'required' => 'Bagian ini wajib diisi.',
        ];
        $this->validate($rules, $messages);

        // Simpan ke database
        $surveyType = SurveyTypes::where('name', 'DOSEN')->first();
        SurveyResponses::create([
            'survey_type_id' => $surveyType->id,
            'form_data' => json_encode($this->formData),
            'question_answers' => json_encode($this->answers),
            'submitted_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->redirect(route('survey.submitted'));
    }

    public function render()
    {
        return view('livewire.home.survey.dosen');
    }
}
