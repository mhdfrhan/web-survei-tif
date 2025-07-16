<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::controller(SurveyController::class)->group(function () {
    Route::get('/survey/vmts', 'vmts')->name('survey.vmts');
    Route::get('/survey/dosen', 'dosen')->name('survey.dosen');
    Route::get('/survey/tenaga-pendidik', 'tendik')->name('survey.tendik');
    Route::get('/survey/mahasiswa', 'mahasiswa')->name('survey.mahasiswa');

    Route::get('/survey-submitted', 'surveySubmitted')->name('survey.submitted');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/survey/vmts', 'surveyVmts')->name('dashboard.survey.vmts');
        Route::get('/dashboard/survey/dosen', 'surveyDosen')->name('dashboard.survey.dosen');
        Route::get('/dashboard/survey/tenaga-pendidik', 'surveyTendik')->name('dashboard.survey.tendik');
        Route::get('/dashboard/survey/mahasiswa', 'surveyMahasiswa')->name('dashboard.survey.mahasiswa');
        Route::get('/dashboard/feedback', 'feedback')->name('dashboard.feedback');

        // rekap data
        Route::get('/dashboard/rekap-data-survey', 'rekapData')->name('dashboard.rekap.data');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
