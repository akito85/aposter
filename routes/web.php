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
Route::get('/', function () {
    return redirect('/dashboard' . '/' . date('Y-m-01') . '/' . date('Y-m-t'));
});
Route::get('/dashboard', 'Pages\DashboardController@index')->name('dashboard');
Route::get('/dashboard/{start}/{end}/{trxID?}', 'Pages\DashboardController@index')->name('dashboard-training');
Route::get('/upload', 'Pages\UploadController@index')->name('upload');
Route::get('/uploadEvagara', 'Pages\UploadEvagaraController@index')->name('uploadEvagara');

Route::post('/fileUploadParticipants', 'Pages\UploadController@fileUploadParticipants')->name('file-participants');
Route::post('/fileUploadResults', 'Pages\UploadController@fileUploadResults')->name('file-results');
Route::post('/fileUploadResultsEvagara', 'Pages\UploadEvagaraController@fileUploadResultsEvagara')->name('file-results');
Route::post('/deleteUploadRecords', 'Pages\UploadController@deleteUploadRecords')->name('delete-records');

Route::get('/checkTrainings', 'SyncTrainingsController@index')->name('check-trainings');