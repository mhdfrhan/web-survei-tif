<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyFormFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_type_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function surveyType()
    {
        return $this->belongsTo(SurveyTypes::class);
    }

    public function getFieldOptionsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setFieldOptionsAttribute($value)
    {
        $this->attributes['field_options'] = $value ? json_encode($value) : null;
    }
}
