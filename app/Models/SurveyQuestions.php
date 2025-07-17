<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_type_id',
        'survey_section_id',
        'question_text',
        'question_type',
        'options',
        'min_value',
        'max_value',
        'rating_labels',
        'is_required',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'rating_labels' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function surveyType()
    {
        return $this->belongsTo(SurveyTypes::class);
    }

    public function getOptionsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = $value ? json_encode($value) : null;
    }

    public function getRatingLabelsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setRatingLabelsAttribute($value)
    {
        $this->attributes['rating_labels'] = $value ? json_encode($value) : null;
    }

    public function isRatingType()
    {
        return $this->question_type === 'rating';
    }

    public function getRatingOptions()
    {
        if (!$this->isRatingType()) {
            return [];
        }

        $options = [];
        for ($i = $this->min_value; $i <= $this->max_value; $i++) {
            $options[$i] = $this->rating_labels[$i] ?? $i;
        }

        return $options;
    }

    public function section()
    {
        return $this->belongsTo(SurveySections::class, 'survey_section_id');
    }
}
