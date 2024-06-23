<?php

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

Route::get('get-from-cities', 'DataController@getFromCities')->name('get-from-cities');
Route::get('{city}/states', 'DataController@getStates')->name('get-states');
Route::get('tours', 'DataController@getTours')->name('get-tours');
Route::get('get-programs', 'DataController@getProgramGroups')->name('get-programs');
Route::get('get-towns', 'DataController@getTowns')->name('get-towns');
Route::get('getRegions', 'DataController@getRegions')->name('get-regions');
Route::get('getCities', 'DataController@getCities')->name('get-cities');
Route::post('store-country', 'DataController@storeCountry')->name('store-country');
Route::post('store-region', 'DataController@storeRegion')->name('store-region');
Route::post('store-city', 'DataController@storeCity')->name('store-city');

Route::get('get-hotels', 'DataController@getHotels')->name('get-hotels');
Route::get('get-categories', 'DataController@getCategories')->name('get-categories');
