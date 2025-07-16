<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function surveyVmts()
    {
        return view('dashboard.survey.vmts');
    }

    public function surveyDosen()
    {
        return view('dashboard.survey.dosen');
    }

    public function surveyTendik()
    {
        return view('dashboard.survey.tendik');
    }

    public function surveyMahasiswa()
    {
        return view('dashboard.survey.mahasiswa');
    }

    public function rekapData()
    {
        
        return view('dashboard.rekap-data');
    }
}
