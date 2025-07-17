<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponses extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_type_id',
        'survey_section_id',
        'respondent_id',
        'form_data',
        'question_answers',
        'submitted_at',
        'ip_address',
        'user_agent',
        'respondent_category'
    ];

    protected $casts = [
        'form_data' => 'array',
        'question_answers' => 'array',
        'submitted_at' => 'datetime'
    ];

    public function surveyType()
    {
        return $this->belongsTo(SurveyTypes::class);
    }

    public function getFormDataAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setFormDataAttribute($value)
    {
        $this->attributes['form_data'] = json_encode($value);
    }

    public function getQuestionAnswersAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setQuestionAnswersAttribute($value)
    {
        $this->attributes['question_answers'] = json_encode($value);
    }

    public function getAnswerForQuestion($questionId)
    {
        return $this->question_answers[$questionId] ?? null;
    }

    public function getFormValue($fieldName)
    {
        return $this->form_data[$fieldName] ?? null;
    }

    public function section()
    {
        return $this->belongsTo(SurveySections::class, 'survey_section_id');
    }
}
