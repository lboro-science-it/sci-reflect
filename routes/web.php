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

Route::post('launch', 'LtiController@launch');

// activity routes
Route::get('a/{activity}', 'ActivityController@show');
Route::put('a/{activity}', 'ActivityController@create');
Route::put('a/{activity}/close', 'ActivityController@close');
Route::put('a/{activity}/open', 'ActivityController@open');

Route::get('eject', 'ActivityController@eject');