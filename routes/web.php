<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\HomeController@redirectToHome')->name('app.home');
Route::post('/absence/store', 'App\Http\Controllers\AbsenceController@storeAbsence')->name('absence.store');
Route::post('/absences/archive', 'App\Http\Controllers\AbsenceController@archiveAbsences')->name('absence.archive');

