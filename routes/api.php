<?php

use App\Http\Controllers\Api\AlbatoIntegrationController;
use App\Http\Controllers\CheckUserController;
use Illuminate\Support\Facades\Route;

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

ApiRoute::group(['namespace' => 'App\Http\Controllers'], function () {
    ApiRoute::get('purchased-module', ['as' => 'api.purchasedModule', 'uses' => 'HomeController@installedModule']);
});

//Route::get('facebook/webhook', [MetaController::class, 'handleFacebookWebhook']);
Route::post('check-me', [CheckUserController::class, 'check']);
Route::get('integration/list', [CheckUserController::class, 'list']);
Route::post('albato/webhook', [AlbatoIntegrationController::class, 'webhook']);

Route::post('lead-create', [\App\Http\Controllers\Api\LeadController::class, 'create']);
