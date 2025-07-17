<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySections extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship dengan survey type
    public function surveyType()
    {
        return $this->belongsTo(SurveyTypes::class);
    }

    // Relationship dengan questions
    public function questions()
    {
        return $this->hasMany(SurveyQuestions::class, 'survey_section_id');
    }

    // Relationship dengan responses
    public function responses()
    {
        return $this->hasMany(SurveyResponses::class);
    }

    // Scope untuk section yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Get URL untuk section
    // public function getUrlAttribute()
    // {
    //     return route('survey.section', [
    //         'surveyType' => $this->surveyType->name,
    //         'section' => $this->slug
    //     ]);
    // }
}
