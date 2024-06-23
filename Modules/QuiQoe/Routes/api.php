<?php

use Illuminate\Support\Facades\Route;
use Modules\QuiQoe\Http\Controllers\QuiQoeController;
use Modules\QuiQoe\Http\Controllers\SelectionToursController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/quiqoe', function (Request $request) {
//    return $request->user();
//});

Route::post('/quiqoe', [QuiQoeController::class, 'index']);

Route::post('/webhook-from-email', [QuiQoeController::class, 'webhookFromEmail']);

Route::post('/selected-tours', [SelectionToursController::class, 'index']);
