<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyTypes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function formFields()
    {
        return $this->hasMany(SurveyFormFields::class, 'survey_type_id')->orderBy('sort_order');
    }
    public function questions()
    {
        return $this->hasMany(SurveyQuestions::class, 'survey_type_id')->orderBy('sort_order');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponses::class, 'survey_type_id');
    }

    public function activeFormFields()
    {
        return $this->formFields()->where('is_active', true);
    }

    public function activeQuestions()
    {
        return $this->questions()->where('is_active', true);
    }
    
}
