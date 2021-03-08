<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', 'Pages\DashboardController@index')->name('root');
Route::get('/dashboard', 'Pages\DashboardController@index')->name('dashboard');
Route::get('/dashboard/{trName?}', 'Pages\DashboardController@index')->name('dashboard-training');
Route::get('/upload', 'Pages\UploadController@index')->name('upload');

Route::post('/fileUploadParticipants', 'Pages\UploadController@fileUploadParticipants')->name('file-participants');
Route::post('/fileUploadResults', 'Pages\UploadController@fileUploadResults')->name('file-results');

Route::get('/checkTrainings', 'SyncTrainingsController@index')->name('check-trainings');