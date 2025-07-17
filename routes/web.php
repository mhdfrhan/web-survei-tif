<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::controller(SurveyController::class)->group(function () {
    Route::get('/survei/vmts', 'vmts')->name('survey.vmts');
    Route::get('/survei/dosen', 'dosen')->name('survey.dosen');
    Route::get('/survei/tenaga-pendidik', 'tendik')->name('survey.tendik');
    Route::get('/survei/mahasiswa', 'mahasiswa')->name('survey.mahasiswa');

    Route::get('/survei/teknik-informatika', 'surveiTIF')->name('survey.tif');

    Route::get('/survei-submitted', 'surveySubmitted')->name('survey.submitted');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/survei/vmts', 'surveyVmts')->name('dashboard.survey.vmts');
        Route::get('/dashboard/survei/dosen', 'surveyDosen')->name('dashboard.survey.dosen');
        Route::get('/dashboard/survei/tenaga-pendidik', 'surveyTendik')->name('dashboard.survey.tendik');
        Route::get('/dashboard/survei/mahasiswa', 'surveyMahasiswa')->name('dashboard.survey.mahasiswa');
        Route::get('/dashboard/feedback', 'feedback')->name('dashboard.feedback');

        // rekap data
        Route::get('/dashboard/rekap-data-survey', 'rekapData')->name('dashboard.rekap.data');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
