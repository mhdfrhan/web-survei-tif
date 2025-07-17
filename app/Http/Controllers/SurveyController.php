<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function vmts()
    {
        return view('survey.vmts');
    }

    public function dosen()
    {
        return view('survey.dosen');
    }

    public function tendik()
    {
        return view('survey.tendik');
    }

    public function mahasiswa()
    {
        return view('survey.mahasiswa');
    }

    public function surveySubmitted()
    {
        return view('survey.submitted');
    }

    public function surveiTIF()
    {
        return view('survey.tif');
    }

    public function surveiTIFSubmitted()
    {
        return view('survey.tif-submitted');
    }
}
