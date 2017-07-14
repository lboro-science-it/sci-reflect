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

// lti launch route
Route::post('launch', 'LtiController@launch');

// staff activity routes
Route::get('a/{activity}', 'ActivityController@show');
Route::put('a/{activity}', 'ActivityController@create');
Route::put('a/{activity}/close', 'ActivityController@close');
Route::put('a/{activity}/open', 'ActivityController@open');

// security routes
Route::get('eject', 'ActivityController@eject');

// student activity routes
Route::get('a/{activity}/student', 'StudentActivityController@show');

// student page routes
Route::post('a/{activity}/student/r/{round_number}/p/{page_number}', 'StudentPageController@process');

// student chart routes
Route::get('a/{activity}/student/r/{round_number}/chart', 'StudentChartController@show');