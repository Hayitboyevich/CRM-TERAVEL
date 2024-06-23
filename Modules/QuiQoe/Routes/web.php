<?php

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

//Route::prefix('quiqoe')->group(function () {
//    Route::post('/', 'QuiQoeController@index');
//});
use Illuminate\Support\Facades\Route;
use Modules\QuiQoe\Http\Controllers\QuiQoeController;
use Modules\QuiQoe\Http\Controllers\SelectionToursController;
//
//Route::post('/quiqoe', [QuiQoeController::class, 'index'])->middleware('auth');
//Route::post('/selected-tours', [SelectionToursController::class, 'index']);
